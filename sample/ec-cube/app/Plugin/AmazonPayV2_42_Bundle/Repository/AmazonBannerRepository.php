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

namespace Plugin\AmazonPayV2_42_Bundle\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\AmazonPayV2_42_Bundle\Entity\AmazonBanner;
use Doctrine\Persistence\ManagerRegistry;

class AmazonBannerRepository extends AbstractRepository
{
    public function __construct(
        ManagerRegistry $managerRegistry,
        $entityClass = AmazonBanner::class
    ) {
        parent::__construct($managerRegistry, $entityClass);
    }
}
