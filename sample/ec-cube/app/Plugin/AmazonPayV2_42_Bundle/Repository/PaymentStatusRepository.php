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

use Eccube\Repository\AbstractRepository;
use Plugin\AmazonPayV2_42_Bundle\Entity\PaymentStatus;
use Doctrine\Persistence\ManagerRegistry;

class PaymentStatusRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $managerRegistry, $entityClass = PaymentStatus::class)
    {
        parent::__construct($managerRegistry, $entityClass);
    }
}
