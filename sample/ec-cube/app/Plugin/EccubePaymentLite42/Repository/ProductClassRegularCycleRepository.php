<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\ProductClassRegularCycle;
use Doctrine\Persistence\ManagerRegistry;

class ProductClassRegularCycleRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductClassRegularCycle::class);
    }
}
