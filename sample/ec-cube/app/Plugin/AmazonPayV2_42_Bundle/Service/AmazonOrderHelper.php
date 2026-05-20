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

namespace Plugin\AmazonPayV2_42_Bundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Plugin\AmazonPayV2_42_Bundle\Entity\Master\AmazonStatus;
use Plugin\AmazonPayV2_42_Bundle\Entity\AmazonTrading;
use Plugin\AmazonPayV2_42_Bundle\Exception\AmazonException;
use Plugin\AmazonPayV2_42_Bundle\Exception\AmazonPaymentException;
use Plugin\AmazonPayV2_42_Bundle\Exception\AmazonSystemException;
use Plugin\AmazonPayV2_42_Bundle\Repository\Master\AmazonStatusRepository;
use Plugin\AmazonPayV2_42_Bundle\Repository\ConfigRepository;
use Plugin\AmazonPayV2_42_Bundle\Service\Method\AmazonPay;
use Plugin\AmazonPayV2_42_Bundle\Service\AmazonRequestService;
use Eccube\Common\Constant;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Entity\Order;
use Eccube\Entity\Shipping;
use Eccube\Entity\Master\CustomerStatus;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\PaymentRepository;
use Eccube\Repository\PluginRepository;
use Eccube\Repository\Master\CustomerStatusRepository;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Repository\Master\PrefRepository;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\Processor\StockReduceProcessor;
use Eccube\Service\PurchaseFlow\Processor\PointProcessor;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as EncoderFactoryInterface;
use Psr\Container\ContainerInterface;

class AmazonOrderHelper
{
    /**
     * @var string プロファイル情報キー
     */
    private $sessionAmazonProfileKey = 'amazon_pay_v2.profile';

    /**
     * @var string プロファイル情報キー
     */
    private $sessionAmazonShippingAddressKey = 'amazon_pay_v2.shipping_address';


    /** @var array */
    private $amazonSettings;

    /**
     * @var PointProcessor
     */
    private $pointProcessor;

    /**
     * @var StockReduceProcessor
     */
    private $stockReduceProcessor;

    public function __construct(
        CustomerRepository $customerRepository,
        BaseInfoRepository $baseInfoRepository,
        PaymentRepository $paymentRepository,
        PluginRepository $pluginRepository,
        CustomerStatusRepository $customerStatusRepository,
        OrderStatusRepository $orderStatusRepository,
        EntityManagerInterface $entityManager,
        EccubeConfig $eccubeConfig,
        PrefRepository $prefRepository,
        RequestStack $requestStack,
        EncoderFactoryInterface $encoderFactory,
        TokenStorageInterface $tokenStorage,
        AmazonStatusRepository $amazonStatusRepository,
        ConfigRepository $configRepository,
        AmazonRequestService $amazonRequestService,
        StockReduceProcessor $stockReduceProcessor,
        PointProcessor $pointProcessor,
        ContainerInterface $container
    ) {
        $this->customerRepository = $customerRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->paymentRepository = $paymentRepository;
        $this->pluginRepository = $pluginRepository;
        $this->customerStatusRepository = $customerStatusRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->entityManager = $entityManager;
        $this->eccubeConfig = $eccubeConfig;
        $this->prefRepository = $prefRepository;
        $this->session = $requestStack->getSession();
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;

        $this->amazonStatusRepository = $amazonStatusRepository;
        $this->configRepository = $configRepository;
        $this->amazonRequestService = $amazonRequestService;

        $this->Config = $this->configRepository->get();

        $this->stockReduceProcessor = $stockReduceProcessor;
        $this->pointProcessor = $pointProcessor;
        $this->container = $container;
    }

    public function getOrderer($shippingAddress)
    {
        $Customer = new Customer();

        // temp value
        $Customer->setName01('　');
        $Customer->setName02('　');
        $Customer->setKana01('　');
        $Customer->setKana02('　');

        $Pref = $this->prefRepository->find(13);
        $Customer->setPref($Pref);

        // amazon
        $profile = unserialize($this->session->get($this->sessionAmazonProfileKey));
        $Customer->setEmail($profile->email);

        $this->convert($Customer, $shippingAddress);

        return $Customer;
    }

    public function initializeAmazonOrder($Order, $Customer)
    {
        $Payment = $this->paymentRepository->findOneBy(['method_class' => AmazonPay::class]);
        $Order->setPayment($Payment);

        // Amazonアカウントが変更されることを考慮
        $Order->setEmail($Customer->getEmail());

        $this->entityManager->persist($Order);

        return $Order;
    }

    /**
     * Amazonのお届け先住所をECに対応付け
     *
     * @param array $arrAmznAddr Amazon登録情報
     */
    public function convert($entity, $shippingAddress)
    {

        // 郵便番号に「-」が含まれる場合は「-」で分割、含まれない場合は前後3桁と4桁で分割
        if (strpos($shippingAddress->postalCode, '-') !== false) {
            $arrPostalCode = explode('-', $shippingAddress->postalCode);
        } else {
            preg_match('/(\d{3})(\d{4})/', $shippingAddress->postalCode, $arrPostalCode);
            unset($arrPostalCode[0]);
            $arrPostalCode = array_values($arrPostalCode);
        }
        $PostalCodeCount = preg_replace('/(-|−)/', '', $shippingAddress->postalCode);
        // 郵便番号が8桁を超えていた場合お客様情報を表示しない.
        if(mb_strlen($PostalCodeCount) >= 9) {
            $arrAmznAddr = [
                'PostalCode' => ' ',
                'CountryCode' => '',
                'StateOrRegion' => '',
                'Name' => '',
                'AddressLine1' => '',
                'AddressLine2' => '',
                'AddressLine3' => '',
                'City' => '',
                'Phone' => ''
            ];
        }

        $entity->setPostalCode(preg_replace('/(-|−)/', '', $shippingAddress->postalCode));

        // 氏名を分割
        $arrName = $this->divideName($shippingAddress->name);
        $entity->setName01($arrName['name01'])
            ->setName02($arrName['name02']);
            if (isset($arrName['kana01'])) {
                $entity->setKana01($arrName['kana01']);
                $entity->setKana02('　');
            } elseif ($entity instanceof Shipping) {
                $entity->setKana01('　')
                ->setKana02('　');
            }

        // カナ補正
        if ($this->Config->getOrderCorrect() == $this->eccubeConfig['amazon_pay_v2']['toggle']['on']) {
            if ($entity instanceof Order) {
                $email = $entity->getEmail();
            } elseif ($entity instanceof Shipping) {
                $Order = $entity->getOrder();
                $email = $Order->getEmail();
            } elseif ($entity instanceof Customer) {
                $email = $entity->getEmail();
            }

            $arrFixKana = $this->reviseKana($entity->getName01(), $entity->getName02(), $email);
            if (!empty($arrFixKana)) {
                $entity->setKana01($arrFixKana['kana01'])
                    ->setKana02($arrFixKana['kana02']);
            } elseif ($entity->getKana01() == null) {
                $entity->setKana01('　')
                    ->setKana02('　');
            }
        }

        /**
         * 都道府県のEntityを取得
         */
        // 東京・青森など表記の揺れを補正する。
        switch ($shippingAddress->stateOrRegion) {
            case "東京":
                $shippingAddress->stateOrRegion = "東京都";
                break;
            case "大阪":
                $shippingAddress->stateOrRegion = "大阪府";
                break;
            case "京都":
                $shippingAddress->stateOrRegion = "京都府";
                break;
            case "東京都":
                break;
            case "大阪府":
                break;
            case "京都府":
                break;
            case "北海道":
                break;
            default:
                if (!preg_match('/県$/u', $shippingAddress->stateOrRegion)) {
                    $shippingAddress->stateOrRegion .= '県';
                }
        }

        // 住所を対応付け
        $convertAmznAddr1 = mb_convert_kana($shippingAddress->addressLine1, 'n', 'utf-8');
        $convertAmznAddr2 = mb_convert_kana($shippingAddress->addressLine2, 'n', 'utf-8');
        if (preg_match("/[0-9]/", $convertAmznAddr1, $matches, PREG_OFFSET_CAPTURE)) {
            $offset = strlen(substr($convertAmznAddr1, 0, $matches[0][1]));
            $AddressLine1Front = mb_substr($convertAmznAddr1, 0, $offset);
            $AddressLine1End = mb_substr($convertAmznAddr1, $offset);
            $addr01 = $shippingAddress->city . $AddressLine1Front;
            $addr02 = $AddressLine1End . $convertAmznAddr2;
        } else {
            $addr01 = $shippingAddress->city . $convertAmznAddr1;
            $addr02 = $convertAmznAddr2;
        }

        if ($shippingAddress->addressLine3 != '') {
            $addr02 .= " " . $shippingAddress->addressLine3;
        }

        $Pref = $this->prefRepository->findOneBy(['name' => $shippingAddress->stateOrRegion]);
        if (!empty($Pref)) {
            $entity->setPref($Pref);
        } else {
            // 存在しない都道府県の場合
            logs('amazon_pay_v2')->info('*** 都道府県マッチングエラー *** addr = ' . var_export($shippingAddress, true));
            $Pref = $this->prefRepository->find(13);
            $entity->setPref($Pref);
            $addr01 = $shippingAddress->stateOrRegion . $addr01;
        }

        $entity->setAddr01($addr01)
            ->setAddr02($addr02);

        $entity->setPhoneNumber(preg_replace('/(-|−)/', '', $shippingAddress->phoneNumber));
    }

    /**
     * 氏名を分割
     *
     * @param string $name
     * @return array ['name01']=>姓、['name02']=>名
     */
    public function divideName($name)
    {
        $arrResult = [];

        $arrName = preg_split('/[ 　]+/u', $name);
        if (count($arrName) == 2) {
            $arrResult['name01'] = $arrName[0];
            $arrResult['name02'] = $arrName[1];
        } else {
            // 苗字リストで氏名を分割
            $arrResult = $this->reviseLastNameList($name);
            if (empty($arrResult)) {
                // 氏名が会員情報や受注情報に存在する場合は、その情報に準じた形に補正
                if ($this->Config->getOrderCorrect() == $this->eccubeConfig['amazon_pay_v2']['toggle']['on']) {
                    $arrFixName = $this->reviseName($name);
                }
                if (!empty($arrFixName)) {
                    $arrResult = $arrFixName;

                    logs('amazon_pay_v2')->info('*** 名前補正 ***');
                } else {
                    $arrResult['name01'] = $name;
                    $arrResult['name02'] = '　';
                }
            }
        }

        return $arrResult;
    }

    /**
     * Objectを取得
     *
     * @param string $search 検索文字列
     * @param string $target name or tel
     * @param string $objectName Entity
     * @return Object
     */
    private function searchObject($search, $target, $objectName)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('o')
            ->from('\Eccube\Entity' . "\\$objectName", 'o');

        if ($target == 'name') {
            $qb
                ->andWhere('CONCAT(o.name01, o.name02) = :name')
                ->setParameter('name', $search)
                ->andWhere("TRIM(o.name02) <> ''");
        }
        return $qb
            ->getQuery()
            ->getResult();
    }

    private function searchObjectByNameAndEmail($name01, $name02, $email, $target, $objectName)
    {
        $qb = $this->entityManager->createQueryBuilder();

        if ($target == 'kana') {
            if ($objectName == 'Order') {
                $qb
                    ->select('o')
                    ->from(Order::class, 'o')
                    ->andWhere('o.name01 = :name01')
                    ->setParameter('name01', $name01)
                    ->andWhere('o.name02 = :name02')
                    ->setParameter('name02', $name02)
                    ->andWhere('o.email = :email')
                    ->setParameter('email', $email)
                    ->andWhere("o.kana01 <> ''")
                    ->andWhere("o.kana02 <> ''")
                    ->andWhere("o.OrderStatus <> :status_pending")
                    ->setParameter('status_pending', OrderStatus::PENDING)
                    ->andWhere("o.OrderStatus <> :status_processing")
                    ->setParameter('status_processing', OrderStatus::PROCESSING)
                    ->andWhere("o.AmazonPayV2AmazonStatus IS NOT NULL")
                    ->orderBy('o.update_date', 'DESC');
            } elseif ($objectName == 'Shipping') {
                $qb
                    ->select('s')
                    ->from(Shipping::class, 's')
                    ->leftJoin('s.Order', 'o')
                    ->andWhere('s.name01 = :name01')
                    ->setParameter('name01', $name01)
                    ->andWhere('s.name02 = :name02')
                    ->setParameter('name02', $name02)
                    ->andWhere('o.email = :email')
                    ->setParameter('email', $email)
                    ->andWhere("s.kana01 <> ''")
                    ->andWhere("s.kana02 <> ''")
                    ->andWhere("o.OrderStatus <> :status_pending")
                    ->setParameter('status_pending', OrderStatus::PENDING)
                    ->andWhere("o.OrderStatus <> :status_processing")
                    ->setParameter('status_processing', OrderStatus::PROCESSING)
                    ->andWhere("o.AmazonPayV2AmazonStatus IS NOT NULL")
                    ->orderBy('o.update_date', 'DESC');
            } elseif ($objectName == 'Customer') {
                $qb
                    ->select('c')
                    ->from(Customer::class, 'c')
                    ->andWhere('c.name01 = :name01')
                    ->setParameter('name01', $name01)
                    ->andWhere('c.name02 = :name02')
                    ->setParameter('name02', $name02)
                    ->andWhere('c.email = :email')
                    ->setParameter('email', $email)
                    ->andWhere("c.kana01 <> ''")
                    ->andWhere("c.kana02 <> ''")
                    ->andWhere("c.v2_amazon_user_id <> ''")
                    ->orderBy('c.update_date', 'DESC');
            }
        }
        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * 氏名を分割
     * 注文者情報、お届け先情報、会員情報の順に同一氏名のObjectを取得
     *
     * @param string $name
     * @return Object
     */
    public function reviseName($name)
    {
        $arrFixName = [];

        // 注文者情報から取得
        $Objects = $this->searchObject($name, 'name', 'Order');
        if (empty($Objects)) {
            // お届け先情報から取得
            $Objects = $this->searchObject($name, 'name', 'Shipping');
            if (empty($Objects)) {
                // 会員情報から取得
                $Objects = $this->searchObject($name, 'name', 'Customer');
            }
        }

        if (!empty($Objects)) {
            $arrFixName['name01'] = $Objects[0]->getName01();
            $arrFixName['name02'] = $Objects[0]->getName02();
        }

        return $arrFixName;
    }

    /**
     * フリガナを分割
     * 注文者情報、お届け先情報、会員情報の順に同一のObjectを取得
     * 判断基準は姓、名、メールアドレスが一致していること
     *
     * @param string $tel
     * @return Object
     */
    public function reviseKana($name01, $name02, $email)
    {
        $arrFixKana = [];

        // 注文者情報から取得
        $Objects = $this->searchObjectByNameAndEmail($name01, $name02, $email, 'kana', 'Order');
        if (empty($Objects)) {
            // お届け先情報から取得
            $Objects = $this->searchObjectByNameAndEmail($name01, $name02, $email, 'kana', 'Shipping');
            if (empty($Objects)) {
                // 会員情報から取得
                $Objects = $this->searchObjectByNameAndEmail($name01, $name02, $email, 'kana', 'Customer');
            }
        }

        if (!empty($Objects)) {
            $arrFixKana['kana01'] = $Objects[0]->getKana01();
            $arrFixKana['kana02'] = $Objects[0]->getKana02();
        }

        return $arrFixKana;
    }

    /**
     * 苗字リストで分割
     * @param string $name
     * @param array $arrLastName
     * @return array $arrName
     */
    public function reviseLastNameList($name)
    {
        $arrName = [];
        // 苗字リストの読み込み
        $fp = fopen($this->eccubeConfig['plugin_data_realdir'] . '/AmazonPayV2_42_Bundle/lastNameList.csv', 'r');
        if ($fp === false) {
            return null;
        }

        // CSVを読み込み名前を分割
        $arrName = null;
        $last_max_len = 0;
        $row = fgetcsv($fp);
        while (($row = fgetcsv($fp)) !== FALSE) {
            if (mb_strpos($name, $row[0], null, 'utf-8') === 0) {
                $cr_len = mb_strlen($row[0], 'utf-8');
                if ($last_max_len > $cr_len) {
                    continue;
                }
                $last_max_len = $cr_len;
                $arrName['name01'] = $row[0];
                $arrName['name02'] = mb_substr($name, $cr_len, null, 'utf-8');
                $arrName['kana01'] = $row[1];
                $arrName['kana02'] = '　';
            }
        }
        fclose($fp);
        return $arrName;
    }

    /**
     * 受注情報を作成
     *
     * @param Order $Order
     * @param Customer $Customer
     */
    public function copyToOrderFromCustomer(Order $Order, Customer $Customer)
    {
        if (empty($Customer)) {
            return;
        }

        if ($Customer->getId()) {
            $Order->setCustomer($Customer);
        }

        $Order
            ->setName01($Customer->getName01())
            ->setName02($Customer->getName02())
            ->setKana01($Customer->getKana01())
            ->setKana02($Customer->getKana02())
            ->setCompanyName($Customer->getCompanyName())
            ->setEmail($Customer->getEmail())
            ->setPhoneNumber($Customer->getPhoneNumber())
            ->setPostalCode($Customer->getPostalCode())
            ->setPref($Customer->getPref())
            ->setAddr01($Customer->getAddr01())
            ->setAddr02($Customer->getAddr02())
            ->setSex($Customer->getSex())
            ->setBirth($Customer->getBirth())
            ->setJob($Customer->getJob());
    }

    /**
     * チェックアウトを完了させる
     *
     * @param Order $Order
     * @param string Amazon参照ID
     * @return string Amazon取引ID
     */
    public function completeCheckout(Order $Order, $amazonCheckoutSessionId)
    {
        // NOTE: AmazonPayAPI仕様上、0円の決済は許容しない.
        if($Order->getPaymentTotal() == 0) {
            throw AmazonPaymentException::create(AmazonPaymentException::ZERO_PAYMENT);
        }

        // チェックアウトセッションを完了し注文を確定させる
        $response = $this->amazonRequestService->completeCheckoutSession($Order, $amazonCheckoutSessionId);

        return $response;
    }

    /**
     * Amazonの受注情報を登録
     *
     * @param Order $Order
     * @param string Amazon取引ID
     */
    public function setAmazonOrder(Order $Order, $chargePermissionId, $chargeId = null)
    {
        $Order->setAmazonPayV2ChargePermissionId($chargePermissionId);
        $AmazonStatus = $this->amazonStatusRepository->find($this->Config->getSale());
        $Order->setAmazonPayV2AmazonStatus($AmazonStatus);
        $payment_total = (int)$Order->getPaymentTotal();
        if ($payment_total * 9 > $this->eccubeConfig['amazon_pay_v2']['max_billable_amount']) {
            $billableAmount = floor($payment_total * 9);
        } else {
            $billableAmount = $payment_total + $this->eccubeConfig['amazon_pay_v2']['max_billable_amount'];
        }

        if ($this->Config->getSale() == $this->eccubeConfig['amazon_pay_v2']['sale']['capture']) {
            $Order->setAmazonPayV2BillableAmount($billableAmount - $payment_total);
            $Order->setPaymentDate(new \DateTime());
        } else {
            $Order->setAmazonPayV2BillableAmount($billableAmount);
        }

        // Amazon取引情報を登録
        $AmazonTrading = new AmazonTrading();

        $AmazonTrading->setOrder($Order);
        $AmazonTrading->setChargePermissionId($chargePermissionId);
        $AmazonTrading->setChargeId($chargeId);
        $AmazonTrading->setAuthoriAmount($payment_total);
        if ($this->Config->getSale() == $this->eccubeConfig['amazon_pay_v2']['sale']['capture']) {
            $AmazonTrading->setCaptureAmount($payment_total);
        } else {
            $AmazonTrading->setCaptureAmount(0);
        }
        $AmazonTrading->setRefundCount(0);

        $Order->addAmazonPayV2AmazonTrading($AmazonTrading);
    }

    /**
     * 会員登録処理
     *
     * @param Order $Order
     * @param array $data request
     * @param Object $profile Amazonプロファイル
     */
    public function registCustomer(Order $Order, $mail_magazine, $profile = null)
    {
        logs('amazon_pay_v2')->info('*** 会員登録処理 start ***');

        if (!$profile) {
            $profile = unserialize($this->session->get($this->sessionAmazonProfileKey));
        }

        /** @var $Customer \Eccube\Entity\Customer */
        $Customer = $this->customerRepository->newCustomer();

        // パスワードをランダムに生成
        $password = $this->createPassword(12);
        $Customer
            ->setSalt(bin2hex(random_bytes(16)))
            ->setPlainPassword($password)
            ->setSecretKey($this->customerRepository->getUniqueSecretKey())
            ->setPoint(0);
        $Customer->setPassword($this->encoderFactory->hashPassword($Customer, $Customer->getPlainPassword()));

        $CustomerStatus = $this->customerStatusRepository->find(CustomerStatus::REGULAR);
        $Customer->setStatus($CustomerStatus);

        // 注文者情報を会員情報に登録
        $Customer->setName01($Order->getName01())
            ->setName02($Order->getName02())
            ->setPostalCode($Order->getPostalCode())
            ->setEmail($Order->getEmail())
            ->setPref($Order->getPref())
            ->setAddr01($Order->getAddr01())
            ->setAddr02($Order->getAddr02())
            ->setPhoneNumber($Order->getPhoneNumber())
            ->setV2AmazonUserId($profile->buyerId);

        // メルマガ管理プラグインの設定値
        if ($this->pluginRepository->findOneBy(['code' => 'MailMagazine42', 'enabled' => true])) {
            if ($mail_magazine) {
                $Customer->setMailmagaFlg(true);
            } else {
                $Customer->setMailmagaFlg(false);
            }
        }

        // ポストキャリアプラグインの設定値
        if ($this->pluginRepository->findOneBy(['code' => 'PostCarrier42', 'enabled' => true])) {
            if ($mail_magazine) {
                $Customer->setPostcarrierFlg(true);
            } else {
                $Customer->setPostcarrierFlg(false);
            }
        }

        $this->entityManager->persist($Customer);
        $this->entityManager->flush();

        // 本会員登録してログイン状態にする
        $token = new UsernamePasswordToken($Customer, 'customer', ['ROLE_USER']);
        $this->tokenStorage->setToken($token);

        logs('amazon_pay_v2')->info('*** 会員登録処理 end ***');

        // メールに追記する会員登録情報
        return $password;
    }


    /**
     * Amazonに売上、取消リクエストを送信
     *
     * @param string $request リクエスト処理
     * @param Order $Order
     * @return array リクエストエラー
     */
    public function adminRequest($request, $Order)
    {
        $amazonErr = '';
        $version = '';
        // Amazon取引一覧を取得
        // Amazonステータスを取得
        if ($Order->getPaymentMethod() === 'AmazonPay') {
            // CV1が有効化でない場合エラーとする
            $V1Enable_flg = $this->pluginRepository->findOneBy(
                array(
                    'code' => 'AmazonPay',
                    'enabled' => Constant::ENABLED
                )
            );
            if (!$V1Enable_flg) {
                $amazonErr = 'CV1受注のリクエストを受け付けましたが、CV1が有効化されていません。';
                return $amazonErr;
            }

            $AmazonTradings = $Order->getAmazonTradings();
            $amazon_status = $Order->getAmazonStatus()->getName();
            $version = 'CV1';
        } elseif ($Order->getPaymentMethod() === 'Amazon Pay') {
            $AmazonTradings = $Order->getAmazonPayV2AmazonTradings();
            $amazon_status = $Order->getAmazonPayV2AmazonStatus()->getName();
            $version = 'CV2';
        }

        // 売上・請求合計金額を算出
        $sumAuthoriAmount = 0;
        $sumCaptureAmount = 0;
        foreach ($AmazonTradings as $AmazonTrading) {
            $sumAuthoriAmount += $AmazonTrading->getAuthoriAmount();
            $sumCaptureAmount += $AmazonTrading->getCaptureAmount();
        }
        // 現在の支払い金額を取得
        $payment_total = (int) $Order->getPaymentTotal();
        // 請求可能額を取得
        if ($version == 'CV1') {
            $billableAmount = $Order->getBillableAmount();
        } elseif ($version == 'CV2') {
            $billableAmount = $Order->getAmazonPayV2BillableAmount();
        }

        // リクエストする請求総額を取得
        $totalBillingAmount = $payment_total - $sumCaptureAmount;

        // リクエストする返金総額を取得
        // 現在の支払総額と差額がある場合は差額分、ない場合は全額
        if ($request == 'cancel' && $payment_total == $sumCaptureAmount) {
            $allRefund_flg = true;
        } else {
            $allRefund_flg = false;
        }
        $cancel_flg = false;

        $totalRefundAmount = $allRefund_flg ? $payment_total : $sumCaptureAmount - $payment_total;

        // リクエスト後の結果
        $resultAmazonTradingInfo = [];
        // 売上の数だけ繰り返す
        foreach ($AmazonTradings as $key => $AmazonTrading) {
            // 取引情報を取得
            if ($version == 'CV1') {
                // CV1受注と見做す
                $amazonChargeId = substr_replace($AmazonTrading->getTradingCode(), "C", 20, 1);
                // Amazon参照IDを取得 CV2APIにおけるamazonChargePermissionIdとして使用
                $amazonChargePermissionId = $Order->getReferenceCode();
            } elseif ($version == 'CV2') {
                $amazonChargePermissionId = $AmazonTrading->getChargePermissionId();
                $amazonChargeId = $AmazonTrading->getChargeId();
            }

            $authoriAmount = $AmazonTrading->getAuthoriAmount();
            $captureAmount = $AmazonTrading->getCaptureAmount();
            $refundCount = $AmazonTrading->getRefundCount();
            // 請求処理
            if ($request == 'capture') {
                // 売上を満額請求済みもしくは全額リクエストした場合
                if ($captureAmount > 0 || $totalBillingAmount == 0) {
                    $r = false;
                    // 請求処理
                } else {
                    // 請求額を算出
                    $billingAmount = $authoriAmount > $totalBillingAmount ? $totalBillingAmount : $authoriAmount;

                    // 売上請求リクエスト
                    $r = $this->amazonRequestService->captureCharge($amazonChargeId, $Order, $billingAmount);

                    if (isset($r->reasonCode)) {
                        logs('amazon_pay_v2')->error('aws request error from admin_order r=' . var_export($r, true));
                        break;
                    }

                    // 取引情報を更新
                    $AmazonTrading->setCaptureAmount($billingAmount);
                    $this->entityManager->flush($AmazonTrading);
                    if ($version == 'CV1') {
                        // 請求金額をリクエストした分差し引く
                        $totalBillingAmount = $totalBillingAmount - $billingAmount;
                        $billableAmount = $billableAmount - $billingAmount;
                    }
                }

                // 追加売上(請求)処理
                if (($payment_total > $sumAuthoriAmount && $amazon_status == 'オーソリ')
                    || ($totalBillingAmount > 0 && $amazon_status == '売上')
                ) {
                    if ($amazon_status == 'オーソリ') {
                        $addAmount = $payment_total - $sumAuthoriAmount;
                    } else {
                        $addAmount = $totalBillingAmount;
                    }

                    $r = $this->amazonRequestService->createCharge($amazonChargePermissionId, $addAmount);
                    if (isset($r->reasonCode) && $r === 'status_failed') {
                        logs('amazon_pay_v2')->error('aws request error from admin_order r=' . var_export($r, true));
                        break;
                    }
                    // Amazon取引IDを取得
                    if (isset($r->chargeId)) {
                        $newAmazonChargeId = $r->chargeId;
                        // 売上請求リクエスト
                        $r = $this->amazonRequestService->captureCharge($newAmazonChargeId, $Order, $addAmount);
                        if (isset($r->reasonCode)) {
                            logs('amazon_pay_v2')->error('aws request error from admin_order r=' . var_export($r, true));
                        break;
                        }

                        // 請求総額を更新
                        $totalBillingAmount = $totalBillingAmount - $addAmount; 

                        // 取引情報を更新
                        if ($version == 'CV1') {
                            $newAmazonTrading = new \Plugin\AmazonPay\Entity\AmazonTrading();
                            $newAmazonTrading->setOrder($Order);
                            $newAmazonTrading->setTradingCode($newAmazonChargeId);
                            $newAmazonTrading->setAuthoriAmount($addAmount);
                            $newAmazonTrading->setCaptureamount($addAmount);
                            $newAmazonTrading->setRefundCount(0);
                            $Order->addAmazonTrading($newAmazonTrading);
                            $billableAmount = $billableAmount - $addAmount;
                        } elseif ($version == 'CV2') {
                            $newAmazonTrading = new AmazonTrading();
                            $newAmazonTrading->setOrder($Order);
                            $newAmazonTrading->setChargePermissionId($r->chargePermissionId);
                            $newAmazonTrading->setChargeId($newAmazonChargeId);
                            $newAmazonTrading->setAuthoriAmount($addAmount);
                            $newAmazonTrading->setCaptureamount($addAmount);
                            $newAmazonTrading->setRefundCount(0);
                            $Order->addAmazonPayV2AmazonTrading($newAmazonTrading);
                        }

                        $this->entityManager->persist($newAmazonTrading);
                        $this->entityManager->flush($newAmazonTrading);
                    }
                }
            } else {
                // 取消処置
                if ($sumCaptureAmount == 0) {
                    // 注文取消リクエスト
                    $cancelPendingCharges = ($amazon_status == 'オーソリ');
                    $r = $this->amazonRequestService->closeChargePermission($amazonChargePermissionId, null, $cancelPendingCharges);
                    $cancel_flg = true;
                    // 返金を全額リクエストした場合
                } else if ($totalRefundAmount == 0) {
                    continue;
                    // 返金処理
                } else {
                    // 売上返金リクエスト
                    // 返金金額を取得
                    $refundAmount = $captureAmount > $totalRefundAmount ? $totalRefundAmount : $captureAmount;
                    $refundCount = $refundCount + 1;
                    if ($refundAmount > 0) {
                        $r = $this->amazonRequestService->createRefund($amazonChargeId, $refundAmount);
                        if ($version == 'CV2') {
                            $AmazonTrading->setRefundId($r->refundId);
                        }
                    }
                    if (isset($r->reasonCode)) {
                        logs('amazon_pay_v2')->error('aws request error from admin_order r = ' . var_export($r, true));
                        break;
                    }

                    // 取引情報を更新
                    $AmazonTrading->setCaptureAmount($captureAmount - $refundAmount);
                    $AmazonTrading->setRefundCount($refundCount);
                    $this->entityManager->flush($AmazonTrading);

                    // 返金総額をリクエストした分差し引く
                    $totalRefundAmount = $totalRefundAmount - $refundAmount;
                }
            }
        }

        if (isset($r->reasonCode)) {
            // スロットルエラーが上限に達した場合、処理を終了
            $amazonErr = "下記のエラーが発生しました。\n"
                . "リクエストを受け付けないため処理を終了しました。\n";
        } else if (is_object($r)) {
            if ($version == 'CV1') {
                // CV1 AmazonStatusRepository呼び出し
                $amazonStatusRepositoryV1 = $this->container->get(\Plugin\AmazonPay\Repository\Master\AmazonStatusRepository::class);
            }
            // キャンセル、全額返金の場合
            if ($cancel_flg || $allRefund_flg) {
                $OrderStatus = $this->orderStatusRepository->find($this->orderStatusRepository->find(OrderStatus::CANCEL));
                // 受注ステータスが発送済みであれば使用ポイント・付与ポイント戻し
                if ($Order->getOrderStatus()->getId() == OrderStatus::DELIVERED) {
                    $Customer = $Order->getCustomer();
                    if ($Customer) {
                        $Customer->setPoint(intval($Customer->getPoint()) - intval($Order->getAddPoint()));
                        $this->pointProcessor->rollback($Order, new PurchaseContext());
                    }
                    // 発送済みでなければ在庫・使用ポイント戻し
                } else if ($Order->getOrderStatus()->getId() != $OrderStatus->getId()) {
                    $this->stockReduceProcessor->rollback($Order, new PurchaseContext());
                    $Customer = $Order->getCustomer();
                    if ($Customer) {
                        $this->pointProcessor->rollback($Order, new PurchaseContext());
                    }
                }
                $Order->setOrderStatus($OrderStatus);
                if ($version == 'CV1') {
                    $AmazonStatus = $amazonStatusRepositoryV1->find(AmazonStatus::CANCEL);
                } elseif ($version == 'CV2') {
                    $AmazonStatus = $this->amazonStatusRepository->find(AmazonStatus::CANCEL);
                }
            } else {
                $Order->setPaymentDate(new \DateTime());
                if ($version == 'CV1') {
                    $Order->setBillableAmount($billableAmount);
                    $AmazonStatus = $amazonStatusRepositoryV1->find(AmazonStatus::CAPTURE);
                } elseif ($version == 'CV2') {
                    $Order->setAmazonPayV2BillableAmount($billableAmount);
                    $AmazonStatus = $this->amazonStatusRepository->find(AmazonStatus::CAPTURE);
                }
            }

            if ($version == 'CV1') {
                $Order->setAmazonStatus($AmazonStatus);
            } elseif ($version == 'CV2') {
                $Order->setAmazonPayV2AmazonStatus($AmazonStatus);
            }

            $this->entityManager->flush();
        } else if ($r === false) {
            // 売上を満額請求済みもしくは全額リクエストした場合
        } else {
            // 取消リクエストに失敗した場合
            $amazonErr = "下記のエラーが発生しました。\n". var_export($r, true) . "\n";
        }

        return $amazonErr;
    }

    /**
     * 都道府県のチェック
     * @param $shippingAddress
     * @return bool
     */
    public function checkShippingPref($shippingAddress)
    {
        /**
         * 都道府県のEntityを取得
         */
        // 東京・青森など表記の揺れを補正する。
        switch ($shippingAddress->stateOrRegion) {
            case "東京":
                $shippingAddress->stateOrRegion = "東京都";
                break;
            case "大阪":
                $shippingAddress->stateOrRegion = "大阪府";
                break;
            case "京都":
                $shippingAddress->stateOrRegion = "京都府";
                break;
            case "東京都":
                break;
            case "大阪府":
                break;
            case "京都府":
                break;
            case "北海道":
                break;
            default:
                if (!preg_match('/県$/u', $shippingAddress->stateOrRegion)) {
                    $shippingAddress->stateOrRegion .= '県';
                }
        }

        $Pref = $this->prefRepository->findOneBy(['name' => $shippingAddress->stateOrRegion]);
        if (empty($Pref)) {
            logs('amazon_pay_v2')->info('*** 都道府県マッチングエラー *** addr = ' . var_export($shippingAddress, true));
            return false;
        }

        return true;
    }

    /**
     * 英数大小文字のパスワードを生成する
     *
     * @param integer $length 文字数
     * @return string
     */
    public function createPassword($length = 8)
    {
        $pwd = [];
        $pwd_strings = [
            "sletter" => range('a', 'z'),
            "cletter" => range('A', 'Z'),
            "number"  => range('0', '9')
        ];

        while (count($pwd) < $length) {
            // ランダムに取得
            $key = array_rand($pwd_strings);
            $pwd[] = $pwd_strings[$key][array_rand($pwd_strings[$key])];
        }
        // 生成したパスワードの順番をランダムに並び替え
        shuffle($pwd);

        return implode($pwd);
    }
}
