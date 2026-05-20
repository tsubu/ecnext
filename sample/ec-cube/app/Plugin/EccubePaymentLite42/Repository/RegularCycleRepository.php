<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\RegularCycle;
use Doctrine\Persistence\ManagerRegistry;

class RegularCycleRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularCycle::class);
    }
}
