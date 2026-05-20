<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\RegularStatus;
use Doctrine\Persistence\ManagerRegistry;

class RegularStatusRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularStatus::class);
    }
}
