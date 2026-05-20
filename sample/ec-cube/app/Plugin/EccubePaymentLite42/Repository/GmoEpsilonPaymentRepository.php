<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\GmoEpsilonPayment;
use Doctrine\Persistence\ManagerRegistry;

class GmoEpsilonPaymentRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GmoEpsilonPayment::class);
    }
}
