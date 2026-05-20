<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\RegularOrderItem;
use Doctrine\Persistence\ManagerRegistry;

class RegularOrderItemRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularOrderItem::class);
    }
}
