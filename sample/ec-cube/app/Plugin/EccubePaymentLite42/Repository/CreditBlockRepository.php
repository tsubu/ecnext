<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\CreditBlock;
use Doctrine\Persistence\ManagerRegistry;

class CreditBlockRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditBlock::class);
    }

    public function get($id = 1)
    {
        return $this->find($id);
    }

    public function deleteAllIpAddressForPassedBlockTime($block_time)
    {
        $date = new \DateTime();
        $date->modify("-$block_time seconds");

        $this->createQueryBuilder('c')
            ->delete()
            ->where('c.block_date < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();
    }
}
