<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\IpBlackList;
use Doctrine\Persistence\ManagerRegistry;

class IpBlackListRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpBlackList::class);
    }
}
