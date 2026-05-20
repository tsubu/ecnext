<?php

/*
 * Amazon Pay V2 for EC-CUBE4
 * Copyright(c) 2023 EC-CUBE CO.,LTD. all rights reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * This program is not free software.
 * It applies to terms of service.
 *
 */

namespace Plugin\AmazonPayV2_42_Bundle\Service;

use Plugin\AmazonPayV2_42_Bundle\Entity\AmazonBanner;
use Plugin\AmazonPayV2_42_Bundle\Entity\Config;
use Plugin\AmazonPayV2_42_Bundle\Repository\AmazonBannerRepository;
use Plugin\AmazonPayV2_42_Bundle\Repository\ConfigRepository;

class AmazonBannerService
{
    /**
     * Amazon Payプラグイン設定リポジトリ
     *
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * Amazonバナーリポジトリ
     *
     * @var AmazonBannerRepository
     */
    protected $amazonBannerRepository;

    public function __construct(
        ConfigRepository $configRepository,
        AmazonBannerRepository $amazonBannerRepository
    ) {
        $this->configRepository = $configRepository;
        $this->amazonBannerRepository = $amazonBannerRepository;
    }

    /**
     * Amazon様バナーについて
     * 選択肢作成用の配列を取得する
     *
     * @param int $page
     * @return 配列
     */
    public function getBannerOptions($page)
    {
        $banners = $this->amazonBannerRepository
            ->createQueryBuilder('a')
            ->where('a.page = :page')
            ->orderBy('a.id')
            ->setParameter('page', $page)
            ->getQuery()->execute();

        $choices = array();
        foreach ($banners as $b) {
            $choices[$b->getName()] = $b->getId();
        }

        return $choices;
    }

    /**
     * トップページに挿入するAmazon様バナーのコードを取得する
     *
     * @return string
     */
    public function getBannerCodeOnTop()
    {
        $id = $this->configRepository->get()->getAmazonBannerSizeOnTop();
        if (is_null($id)) {
            $banner = $this->getDefaultBanner(AmazonBanner::PAGE_TOP);
        } else {
            $banner = $this->amazonBannerRepository->find($id);
        }
        $config = $this->configRepository->get();

        return str_replace('{{MERCHANT_ID}}', $config->getSellerId(), $banner->getCode());
    }

    /**
     * カート画面に挿入するAmazon様バナーのコードを取得する
     *
     * @return string
     */
    public function getBannerCodeOnCart()
    {
        $id = $this->configRepository->get()->getAmazonBannerSizeOnCart();
        if (is_null($id)) {
            $banner = $this->getDefaultBanner(AmazonBanner::PAGE_CART);
        } else {
            $banner = $this->amazonBannerRepository->find($id);
        }
        $config = $this->configRepository->get();

        return str_replace('{{MERCHANT_ID}}', $config->getSellerId(), $banner->getCode());
    }

    /**
     * デフォルトバナーを取得する
     *
     * @param integer $page ページ種別
     * @return デフォルト表示するバナーのレコード
     */
    protected function getDefaultBanner($page)
    {
        $banners = $this->amazonBannerRepository
            ->createQueryBuilder('a')
            ->where('a.page = :page')
            ->andWhere('a.isDefault = 1')
            ->setParameter('page', $page)
            ->getQuery()->execute();

        return $banners[0];
    }
}
