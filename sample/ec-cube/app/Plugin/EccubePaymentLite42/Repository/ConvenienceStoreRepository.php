<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\ConvenienceStore;
use Doctrine\Persistence\ManagerRegistry;

class ConvenienceStoreRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConvenienceStore::class);
    }
}
