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

namespace Plugin\AmazonPayV2_42_Bundle\Repository\Master;

use Eccube\Repository\AbstractRepository;
use Plugin\AmazonPayV2_42_Bundle\Entity\Master\AmazonStatus;
use Doctrine\Persistence\ManagerRegistry;

class AmazonStatusRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry, $entityClass = AmazonStatus::class)
    {
        parent::__construct($managerRegistry, $entityClass);
    }
}
