<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\RegularShipping;
use Doctrine\Persistence\ManagerRegistry;

class RegularShippingRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularShipping::class);
    }
}
