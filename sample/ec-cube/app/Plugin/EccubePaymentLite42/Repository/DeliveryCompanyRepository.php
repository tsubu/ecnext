<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\DeliveryCompany;
use Doctrine\Persistence\ManagerRegistry;

class DeliveryCompanyRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryCompany::class);
    }
}
