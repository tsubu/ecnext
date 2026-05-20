<?php

/*
 * Amazon Pay V2 for EC-CUBE4.2
 * Copyright(c) 2023 EC-CUBE CO.,LTD. all rights reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * This program is not free software.
 * It applies to terms of service.
 *
 */

namespace Plugin\AmazonPayV2_42_Bundle;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Container\ContainerInterface;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Common\Constant;
use Eccube\Entity\Payment;
use Eccube\Entity\PaymentOption;
use Eccube\Entity\Page;
use Eccube\Entity\PageLayout;
use Eccube\Entity\Csv;
use Eccube\Entity\Plugin;
use Eccube\Entity\Layout;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\Master\CsvType;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\PageRepository;
use Eccube\Repository\LayoutRepository;
use Eccube\Repository\PageLayoutRepository;
use Eccube\Repository\PaymentRepository;
use Eccube\Repository\DeliveryRepository;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\CsvRepository;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Plugin\AmazonPayV2_42_Bundle\Entity\Master\AmazonStatus;
use Plugin\AmazonPayV2_42_Bundle\Entity\Config;
use Plugin\AmazonPayV2_42_Bundle\Form\Type\Master\ConfigTypeMaster as Master;
use Plugin\AmazonPayV2_42_Bundle\Service\Method\AmazonPay;
use Eccube\Repository\PluginRepository;
use Eccube\Service\PluginService;
use Plugin\AmazonPayV2_42_Bundle\Entity\AmazonBanner;

class PluginManager extends AbstractPluginManager
{
    /**
     * PluginManager constructor.
     */
    public function __construct()
    {
        // コピー元のディレクトリ
        $this->origin_css = __DIR__ . '/Resource/copy/css';
        $this->origin_plugin_data = __DIR__ . '/Resource/PluginData';

        // コピー先のディレクトリ
        $this->target_css = __DIR__ . '/../../../html/template/default/assets/css';
        $this->target_plugin_data = __DIR__ . '/../../PluginData/AmazonPayV2_42_Bundle';

        $this->target_user_data = __DIR__ . '/../../../html/user_data';
    }

    public function install(array $config, ContainerInterface $serviceContainer)
    {
        // リソースファイルのコピー
        $this->copyAssets($serviceContainer);

        // lastNameListのコピー
        $file = new Filesystem();
        if (!$file->exists($this->target_plugin_data . '/lastNameList.csv')) {
            $file->copy($this->origin_plugin_data . '/lastNameList.csv', $this->target_plugin_data . '/lastNameList.csv');
        }

        // amazon_pay_config.iniの設定
        $this->setConfigIni();

        // メール送信
        try {
            log_info("AmazonPayプラグイン(V2)インストールメール送信処理開始");
            $this->sendAutoMail($config, $serviceContainer, 'インストール');
        } catch (\Exception $e) {
            log_info("AmazonPayプラグイン(V2)インストールメール送信に失敗しました。" . $e->getMessage());
        }
        log_info("AmazonPayプラグイン(V2)インストールメール送信処理終了");
    }

    public function enable(array $config, ContainerInterface $serviceContainer)
    {
        $PluginRepository = $serviceContainer->get("doctrine.orm.entity_manager")->getRepository(Plugin::class);
        //$PluginService = $serviceContainer->get('Eccube\Service\PluginService');

        $Plugin = $PluginRepository->findByCode('AmazonPayV2_42_Bundle');
        //$PluginService->generateProxyAndUpdateSchema($Plugin, $config);

        $this->createAmazonPay($serviceContainer);
        $this->createConfigCsv($serviceContainer);
        $this->createAmazonPage($serviceContainer);

        $this->createPlgAmazonPayBanner($serviceContainer);
        $this->createPlgAmazonPayConfig($serviceContainer);
        $this->createPlgAmazonPayStatus($serviceContainer);
    }

    public function disable(array $config, ContainerInterface $serviceContainer)
    {
        $this->disableAmazonPay($serviceContainer);
    }

    public function uninstall(array $config, ContainerInterface $serviceContainer)
    {
        // リソースファイルの削除
        $this->removeAssets($serviceContainer);

        // lastNameListの削除
        $file = new Filesystem();
        $file->remove($this->target_plugin_data . '/lastNameList.csv');

        // 関連DB削除
        $this->removeConfigCsv($serviceContainer);
        $this->removeAmazonPage($serviceContainer);

        // メール送信
        try {
            log_info("AmazonPayプラグイン(V2)削除メール送信処理開始");
            $this->sendAutoMail($config, $serviceContainer, '削除');
        } catch (\Exception $e) {
            log_info("AmazonPayプラグイン(V2)削除メール送信に失敗しました。" . $e->getMessage());
        }
        log_info("AmazonPayプラグイン(V2)削除メール送信処理終了");
    }

    public function update(array $config, ContainerInterface $serviceContainer)
    {
        // リソースファイルのアップデート
        $this->updateAssets($serviceContainer);
        // ページのアップデート
        $this->createAmazonPage($serviceContainer);

        $this->createPlgAmazonPayBanner($serviceContainer);

        // メール送信
        try {
            log_info("AmazonPayプラグイン(V2)アップデートメール送信処理開始");
            $this->sendAutoMail($config, $serviceContainer, 'アップデート');
        } catch (\Exception $e) {
            log_info("AmazonPayプラグイン(V2)アップデートメール送信に失敗しました。" . $e->getMessage());
        }
    }

    private function createAmazonPay(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');
        $paymentRepository = $entityManage->getRepository(Payment::class);

        $Payment = $paymentRepository->findOneBy(['method_class' => AmazonPay::class]);
        if ($Payment) {
            $Payment->setVisible(true);
            $entityManage->flush($Payment);

            return;
        }

        $Payment = $paymentRepository->findOneBy([], ['sort_no' => 'DESC']);
        $sortNo = $Payment ? $Payment->getSortNo() + 1 : 1;

        // AmazonPay
        $Payment = new Payment();
        $Payment->setCharge(0);
        $Payment->setSortNo($sortNo);
        $Payment->setVisible(true);
        $Payment->setMethod('Amazon Pay');
        $Payment->setMethodClass(AmazonPay::class);
        $Payment->setRuleMin(0);

        $entityManage->persist($Payment);
        $entityManage->flush($Payment);
    }

    private function disableAmazonPay(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');
        $paymentRepository = $entityManage->getRepository(Payment::class);

        $Payment = $paymentRepository->findOneBy(['method_class' => AmazonPay::class]);
        if ($Payment) {
            $Payment->setVisible(false);
            $entityManage->flush($Payment);
        }
    }

    private function createConfigCsv(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');
        $csvTypeRepository = $entityManage->getRepository(CsvType::class);
        $csvRepository = $entityManage->getRepository(Csv::class);

        $OrderCsvType = $csvTypeRepository->find(3);

        $LastCsv = $csvRepository->findOneBy(['CsvType' => $OrderCsvType], ['sort_no' => 'DESC']);

        $sortNo = $LastCsv->getSortNo();

        $arrCsv = [
            [
                'entity_name' => 'Eccube\\Entity\\Order',
                'field_name' => 'amazonpay_v2_charge_permission_id',
                'reference_field_name' => null,
                'disp_name' => 'Amazon参照ID',
            ],
            [
                'entity_name' => 'Eccube\\Entity\\Order',
                'field_name' => 'AmazonPayV2AmazonStatus',
                'reference_field_name' => 'name',
                'disp_name' => 'Amazon状況',
            ]
        ];

        foreach ($arrCsv as $c) {
            $Csv = $csvRepository->findOneBy(['disp_name' => $c['disp_name']]);
            if ($Csv) {
                continue;
            }
            $Csv = new Csv();

            $Csv->setCsvType($OrderCsvType);
            $Csv->setEntityName($c['entity_name']);
            $Csv->setFieldName($c['field_name']);
            $Csv->setReferenceFieldName($c['reference_field_name']);
            $Csv->setDispName($c['disp_name']);
            $Csv->setSortNo($sortNo++);
            $Csv->setCreateDate(new \DateTime());
            $Csv->setUpdateDate(new \DateTime());

            $entityManage->persist($Csv);
            $entityManage->flush($Csv);
        }
    }

    private function removeConfigCsv(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');
        $csvTypeRepository = $entityManage->getRepository(CsvType::class);
        $csvRepository = $entityManage->getRepository(Csv::class);

        $OrderCsvType = $csvTypeRepository->find(3);

        $arrCsv = [
            [
                'entity_name' => 'Eccube\\Entity\\Order',
                'field_name' => 'amazonpay_v2_charge_permission_id',
                'reference_field_name' => null,
                'disp_name' => 'Amazon参照ID',
            ],
            [
                'entity_name' => 'Eccube\\Entity\\Order',
                'field_name' => 'AmazonPayV2AmazonStatus',
                'reference_field_name' => 'name',
                'disp_name' => 'Amazon状況',
            ]
        ];

        foreach ($arrCsv as $c) {
            $Csv = $csvRepository->findOneBy($c);
            if ($Csv) {
                $entityManage->remove($Csv);
                $entityManage->flush();
            }
        }
    }

    private function createAmazonPage(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');

        // ページ追加
        $pageRepository = $entityManage->getRepository(Page::class);
        $layoutRepository = $entityManage->getRepository(Layout::class);
        $pageLayoutRepository = $entityManage->getRepository(PageLayout::class);
        $Layout = $layoutRepository->find(2);

        $LastPageLayout = $pageLayoutRepository->findOneBy([], ['sort_no' => 'DESC']);
        $sortNo = $LastPageLayout->getSortNo();

        $arrPage = [
            [
                'page_name' => '商品購入(Amazon Pay)',
                'url' => 'amazon_pay_shopping',
                'file_name' => 'Shopping/index'
            ],
            [
                'page_name' => '商品購入(Amazon Pay)/ご注文確認',
                'url' => 'amazon_pay_shopping_confirm',
                'file_name' => 'Shopping/confirm'
            ],
        ];

        foreach ($arrPage as $p) {
            $Page = $pageRepository->findOneBy(['url' => $p['url']]);
            if ($Page) {
                continue;
            }

            $Page = new Page();
            $Page->setName($p['page_name']);
            $Page->setUrl($p['url']);
            $Page->setFileName($p['file_name']);
            $Page->setEditType(Page::EDIT_TYPE_DEFAULT);
            $Page->setCreateDate(new \DateTime());
            $Page->setUpdateDate(new \DateTime());
            $Page->setMetaRobots('noindex');

            $entityManage->persist($Page);
            $entityManage->flush($Page);

            $PageLayout = new PageLayout();
            $PageLayout->setPage($Page);
            $PageLayout->setPageId($Page->getId());
            $PageLayout->setLayout($Layout);
            $PageLayout->setLayoutId($Layout->getId());
            $PageLayout->setSortNo($sortNo++);

            $entityManage->persist($PageLayout);
            $entityManage->flush($PageLayout);
        }
    }

    private function removeAmazonPage(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');
        $pageRepository = $entityManage->getRepository(Page::class);

        $arrPage = [
            [
                'name' => '商品購入(Amazon Pay)',
                'url' => 'amazon_pay_shopping',
                'file_name' => 'Shopping/index'
            ],
            [
                'name' => '商品購入(Amazon Pay)/ご注文確認',
                'url' => 'amazon_pay_shopping_confirm',
                'file_name' => 'Shopping/confirm'
            ],
        ];

        foreach ($arrPage as $p) {
            $Page = $pageRepository->findOneBy($p);
            if ($Page) {
                foreach ($Page->getPageLayouts() as $PageLayout) {
                    $Page->removePageLayout($PageLayout);
                    $entityManage->remove($PageLayout);
                    $entityManage->flush($PageLayout);
                }

                $entityManage->remove($Page);
                $entityManage->flush();
            }
        }
    }

    /**
     * create table plg_amazon_pay_config
     *
     * @param ContainerInterface $serviceContainer
     */
    public function createPlgAmazonPayConfig(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');
        /** @var \Plugin\AmazonPayV2_42_Bundle\Entity\Config $Config */
        $Config = $entityManage->find(Config::class, 1);
        if ($Config) {
            // eccubeConfig['amazon_pay_v2']['env']みたいに値を取得
            $amazonPayEnvs = $serviceContainer->get(\Eccube\Common\EccubeConfig::class)->get('amazon_pay_v2')['env'];
            if (in_array($Config->getEnv(), $amazonPayEnvs)) {
                $Config->setAmazonAccountMode(2);
            } else {
                $Config->setEnv(1);
                $Config->setAmazonAccountMode(1);
            }
            $entityManage->flush();

            return;
        }

        $bannerRepository = $entityManage->getRepository(AmazonBanner::class);
        $firstBannerOnTop = $bannerRepository->findOneBy(['page' => AmazonBanner::PAGE_TOP, 'isDefault' => true]);
        $firstBannerOnCart = $bannerRepository->findOneBy(['page' => AmazonBanner::PAGE_CART, 'isDefault' => true]);

        // プラグイン情報初期セット
        // 動作設定
        $Config = new Config();
        $Config->setAmazonAccountMode(Master::ACCOUNT_MODE['SHARED']);
        $Config->setEnv(Master::ENV['SANDBOX']);
        $Config->setPrivateKeyPath('app/PluginData/AmazonPayV2_42_Bundle/AmazonPay_*.pem');
        $Config->setSale(Master::SALE['AUTORI']);
        $Config->setUseConfirmPage(false);
        $Config->setAutoLogin(true);
        $Config->setLoginRequired(false);
        $Config->setOrderCorrect(true);
        $Config->setMailNotices(null);
        // カート画面
        $Config->setUseCartButton(true);
        $Config->setCartButtonColor('Gold');
        $Config->setCartButtonPlace(Master::CART_BUTTON_PLACE['AUTO']);
        // MYページ/ログイン
        $Config->setUseMypageLoginButton(false);
        $Config->setMypageLoginButtonColor('Gold');
        $Config->setMypageLoginButtonPlace(Master::MYPAGE_LOGIN_BUTTON_PLACE['AUTO']);
        // AmazonPayバナー
        $Config->setUseAmazonBannerOnTop(true);
        $Config->setUseAmazonBannerOnCart(true);
        $Config->setAmazonBannerSizeOnTop($firstBannerOnTop->getId());
        $Config->setAmazonBannerSizeOnCart($firstBannerOnCart->getId());

        $entityManage->persist($Config);
        $entityManage->flush($Config);
    }

    public function createPlgAmazonPayStatus(ContainerInterface $serviceContainer)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');

        $statuses = [
            AmazonStatus::AUTHORI => 'オーソリ',
            AmazonStatus::CAPTURE => '売上',
            AmazonStatus::CANCEL => '取消',
        ];

        $i = 0;
        foreach ($statuses as $id => $name) {
            $AmazonStatus = $entityManage->find(AmazonStatus::class, $id);
            if ($AmazonStatus) {
                continue;
            }

            $AmazonStatus = new AmazonStatus();

            $AmazonStatus->setId($id);
            $AmazonStatus->setName($name);
            $AmazonStatus->setSortNo($i++);

            $entityManage->persist($AmazonStatus);
            $entityManage->flush($AmazonStatus);
        }
    }

    /**
     * バナーマスタの内容を登録する
     *
     * @param ContainerInterface $container コンテナ
     * @return void
     */
    public function createPlgAmazonPayBanner(ContainerInterface $container)
    {
        $entityManage = $container->get('doctrine.orm.entity_manager');

        $banners = [
            '728x90' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=01_Amazon_Pay_BBP_728x90.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/01_Amazon_Pay_BBP_728x90.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=728&width=90"/></a>',
            '710x160' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=02_Amazon_Pay_BBP_710x160.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/02_Amazon_Pay_BBP_710x160.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=710&width=160"/></a>',
            '700x350' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=03_Amazon_Pay_BBP_700x350.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/03_Amazon_Pay_BBP_700x350.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=700&width=350"/></a>',
            '660x660' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=04_Amazon_Pay_BBP_660x660.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/04_Amazon_Pay_BBP_660x660.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=660&width=660"/></a>',
            '640x100' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=05_Amazon_Pay_BBP_640x100.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/05_Amazon_Pay_BBP_640x100.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=640&width=100"/></a>',
            '590x354' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=06_Amazon_Pay_BBP_590x354.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/06_Amazon_Pay_BBP_590x354.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=590&width=354"/></a>',
            '336x280' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=07_Amazon_Pay_BBP_336x280.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/07_Amazon_Pay_BBP_336x280.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=336&width=280"/></a>',
            '320x100' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=08_Amazon_Pay_BBP_320x100.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/08_Amazon_Pay_BBP_320x100.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=320&width=100"/></a>',
            '320x50' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=09_Amazon_Pay_BBP_320x50.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/09_Amazon_Pay_BBP_320x50.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=320&width=50"/></a>',
            '300x250' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=10_Amazon_Pay_BBP_300x250.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/10_Amazon_Pay_BBP_300x250.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=300&width=250"/></a>',
            '250x250' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=11_Amazon_Pay_BBP_250x250.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/11_Amazon_Pay_BBP_250x250.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=250&width=250"/></a>',
            '160x600' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=12_Amazon_Pay_BBP_160x600.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/12_Amazon_Pay_BBP_160x600.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=160&width=600"/></a>',
            '1500x750' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=13_Amazon_Pay_BBP_1500x750.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/13_Amazon_Pay_BBP_1500x750.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=1500&width=750"/></a>',
            '1000x120' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=14_Amazon_Pay_BBP_1000x120.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/14_Amazon_Pay_BBP_1000x120.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=1000&width=120"/></a>',
            '990x200' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=15_Amazon_Pay_BBP_990x200.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/15_Amazon_Pay_BBP_990x200.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=990&width=200"/></a>',
            '750x750' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=16_Amazon_Pay_BBP_750x750.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/16_Amazon_Pay_BBP_750x750.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=750&width=750"/></a>',
            '750x220' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=17_Amazon_Pay_BBP_750x220.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/17_Amazon_Pay_BBP_750x220.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=750&width=220"/></a>',
            '737x128' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=18_Amazon_Pay_BBP_737x128.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/18_Amazon_Pay_BBP_737x128.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=737&width=128"/></a>',
            '120x76' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=19_Amazon_Pay_BBP_120x76.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/19_Amazon_Pay_BBP_120x76.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=120&width=76"/></a>',
            '240x76' => '<a href="https://apay-up-banner.com?merchantId={{MERCHANT_ID}}&banner=20_Amazon_Pay_BBP_240x76.png&locale=ja_JP&utm_source={{MERCHANT_ID}}" target="_blank"><img src="https://apay-up-banner.com/banner/20_Amazon_Pay_BBP_240x76.png?merchantId={{MERCHANT_ID}}&locale=ja_JP&utm_source={{MERCHANT_ID}}&height=240&width=76"/></a>'
        ];

        $amazonBannerRepository = $entityManage->getRepository(AmazonBanner::class);
        // トップページ用レコードの作成
        foreach ($banners as $key => $value) {
            $code = 
            '    <script>' .
            '        $(function () {' .
            '            /* ボタンを設置 */' .
            '            $("div#AmazonBanner").append($("div#div_AmazonBanner"));' .
            '        });' .
            '    </script>' .
            '    ' .
            '    <div class="hidden">' .
            '        <div id="div_AmazonBanner">' .
                        $value .
            '        </div>' .
            '    </div>';

            // すでにレコードがあれば更新する
            $record = $amazonBannerRepository->findOneBy(['name' => $key, 'page' => AmazonBanner::PAGE_TOP]);
            if (isset($record)) {
                $record->setCode($code);
                $entityManage->persist($record);
                $entityManage->flush($record);
            } else {
                $AmazonBanner = new AmazonBanner();
                $AmazonBanner->setPage(AmazonBanner::PAGE_TOP);
                $AmazonBanner->setName($key);
                $AmazonBanner->setCode($code);
                if ($key == '1000x120') {
                    $AmazonBanner->setIsDefault(true);
                }
                $entityManage->persist($AmazonBanner);
                $entityManage->flush($AmazonBanner);
            }
        }

        // カート画面用レコードの作成
        foreach ($banners as $key => $value) {
            $code = 
            '    <script>' .
            '        $(function () {' .
            '            /* ボタンを設置 */' .
            '            $("div#AmazonBanner").append($("div#div_AmazonBanner"));' .
            '        });' .
            '    </script>' .
            '    ' .
            '    <div class="hidden">' .
            '        <div id="div_AmazonBanner">' .
                        $value .
            '        </div>' .
            '    </div>';

            // すでにレコードがあれば更新する
            $record = $amazonBannerRepository->findOneBy(['name' => $key, 'page' => AmazonBanner::PAGE_CART]);
            if (isset($record)) {
                $record->setCode($code);
                $entityManage->persist($record);
                $entityManage->flush($record);
            } else {
                $AmazonBanner = new AmazonBanner();
                $AmazonBanner->setPage(AmazonBanner::PAGE_CART);
                $AmazonBanner->setName($key);
                $AmazonBanner->setCode($code);
                if ($key == '1000x120') {
                    $AmazonBanner->setIsDefault(true);
                }
                $entityManage->persist($AmazonBanner);
                $entityManage->flush($AmazonBanner);
            }
        }

        /** @var \Plugin\AmazonPayV2_42_Bundle\Entity\Config $Config */
        $Config = $entityManage->find(Config::class, 1);
        if ($Config) {
            // すでに設定レコードがあれば、AmazonBannerの初期値を設定する
            $firstBannerOnTop = $amazonBannerRepository->findOneBy(['page' => AmazonBanner::PAGE_TOP, 'isDefault' => true]);
            $firstBannerOnCart = $amazonBannerRepository->findOneBy(['page' => AmazonBanner::PAGE_CART, 'isDefault' => true]);

            // AmazonPayバナー
            $tmp = $Config->getUseAmazonBannerOnTop();
            if (!isset($tmp)) {
                $Config->setUseAmazonBannerOnTop(true);
            }
            $tmp = $Config->getUseAmazonBannerOnCart();
            if (!isset($tmp)) {
                $Config->setUseAmazonBannerOnCart(true);
            }
            $tmp  = $Config->getAmazonBannerSizeOnTop();
            if (!isset($tmp) || $tmp == 0) {
                $Config->setAmazonBannerSizeOnTop($firstBannerOnTop->getId());
            }
            $tmp = $Config->getAmazonBannerSizeOnCart();
            if (!isset($tmp) || $tmp == 0) {
                $Config->setAmazonBannerSizeOnCart($firstBannerOnCart->getId());
            }

            $entityManage->persist($Config);
            $entityManage->flush($Config);
        }
    }

    /**
     * ファイルをコピー
     */
    private function copyAssets(ContainerInterface $serviceContainer)
    {
        $file = new Filesystem();

        // css
        $file->mkdir($this->target_css);
        $file->mirror($this->origin_css, $this->target_css);
    }

    /**
     * コピーしたファイルを削除
     */
    private function removeAssets(ContainerInterface $serviceContainer)
    {
        $file = new Filesystem();

        // css
        $file->remove($this->target_css . '/amazon_shopping_v2.css');
    }

    /**
     * ファイルをアップデート
     */
    private function updateAssets(ContainerInterface $serviceContainer)
    {
        $file = new Filesystem();
        if (!$file->exists($this->target_plugin_data . '/lastNameList.csv')) {
            $file->copy($this->origin_plugin_data . '/lastNameList.csv', $this->target_plugin_data . '/lastNameList.csv');
        }
        // css
        $file->remove($this->target_css . '/amazon_shopping_v2.css');
        $file->copy($this->origin_css . '/amazon_shopping_v2.css', $this->target_css . '/amazon_shopping_v2.css');
    }

    /**
     * 自動メール送信 ※インストール、アンイストール、アップデート時
     *
     * @param string $process
     */
    public function sendAutoMail($config, $serviceContainer, $process)
    {
        $entityManage = $serviceContainer->get('doctrine.orm.entity_manager');
        $baseInfoRepository = $entityManage->getRepository(BaseInfo::class);
        $BaseInfo = $baseInfoRepository->get();

        $url = '';
        if(isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
            $url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];
            $url = substr($url, 0, strrpos($url, 'store') - 1);
            $url = substr($url, 0, strrpos($url, '/') + 1);
        }

        $datetime = date('Y-m-d H:i:s');

        // EC-CUBEのバージョン取得
        $version = Constant::VERSION;
        // プラグインのバージョン取得
        $plugin_version = $config['version'];

        $body = <<<__EOS__
Amazon Pay プラグインサポート各位

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■　プラグイン{$process}のお知らせ　■
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

以下のECサイトでAmazon Pay V2 プラグインが{$process}されました。

【店名】{$BaseInfo->getShopName()}
【EC-CUBE】{$version}
【プラグイン】{$plugin_version}
【URL】{$url}
【メールアドレス】{$BaseInfo->getEmail01()}
【処理日時】{$datetime}


※本メールは、配信専用です。
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Amazon Pay　プラグインサポート
URL：https://www.ec-cube.net/product/amazonpay.php
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
__EOS__;

        $message = new Email();
        $message
            ->subject('[' . $BaseInfo->getShopName() . '] ' . 'プラグイン' . $process . '処理のお知らせ【Amazon Pay V2】')
            ->from(new Address($BaseInfo->getEmail03(), $BaseInfo->getShopName()))
            ->to(new Address('amazonpay-support@ec-cube.net', 'amazon'))
            ->text($body);

        $transport = Transport::fromDsn($serviceContainer->get(\Eccube\Common\EccubeConfig::class)->get('eccube_mailer_dsn'));
        $mailer = new Mailer($transport);
        $mailer->send($message);
    }

    private function setConfigIni()
    {
        $eccubePlatform = env('ECCUBE_PLATFORM');
        if (!($eccubePlatform === 'ec-cube.co')) {
            // ECCUBEクラウド版のみ行うため
            return;
        }

        // 16桁で生成
        $rand = \Eccube\Util\StringUtil::random();
        file_put_contents(__DIR__ . '/amazon_pay_config.ini', "prefix = '{$rand}'");
    }
}
