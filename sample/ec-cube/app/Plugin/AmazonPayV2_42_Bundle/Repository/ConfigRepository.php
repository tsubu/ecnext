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

namespace Plugin\AmazonPayV2_42_Bundle\Repository;

use Eccube\Common\EccubeConfig;
use Eccube\Repository\AbstractRepository;
use Plugin\AmazonPayV2_42_Bundle\Entity\Config;
use Doctrine\Persistence\ManagerRegistry;

class ConfigRepository extends AbstractRepository
{
    public function __construct(
        EccubeConfig $eccubeConfig,
        ManagerRegistry $managerRegistry,
        $entityClass = Config::class
    ) {
        parent::__construct($managerRegistry, $entityClass);

        $this->eccubeConfig = $eccubeConfig;
    }

    public function get($setting = false)
    {
        $Config = $this->find(1);

        if (
            $setting === false &&
            $Config->getAmazonAccountMode() == $this->eccubeConfig['amazon_pay_v2']['account_mode']['shared']
        ) {
            $Config->setSellerId($this->eccubeConfig['amazon_pay_v2']['test_account']['seller_id']);
            $Config->setPublicKeyId($this->eccubeConfig['amazon_pay_v2']['test_account']['public_key_id']);
            $Config->setPrivateKeyPath($this->eccubeConfig['amazon_pay_v2']['test_account']['private_key_path']);
            $Config->setClientId($Config->getTestClientId());
        }

        return $Config;
    }
}
