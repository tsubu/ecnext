<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\PaymentStatus;
use Doctrine\Persistence\ManagerRegistry;

class PaymentStatusRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentStatus::class);
    }
}
