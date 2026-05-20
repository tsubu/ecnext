<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\RegularCycleType;
use Doctrine\Persistence\ManagerRegistry;

class RegularCycleTypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularCycleType::class);
    }
}
