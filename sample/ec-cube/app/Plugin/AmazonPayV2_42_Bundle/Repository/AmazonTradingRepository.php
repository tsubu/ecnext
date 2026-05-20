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

use Doctrine\ORM\EntityRepository;

class AmazonTradingRepository extends EntityRepository
{
    /** @var array */
    public $config;

    public function setConfig(array $config)
    {
        $this->config = $config;
    }
}
