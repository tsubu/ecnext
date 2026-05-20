<?php

namespace Plugin\EccubePaymentLite42\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\EccubePaymentLite42\Entity\CreditAccessLog;
use Doctrine\Persistence\ManagerRegistry;

class CreditAccessLogRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditAccessLog::class);
    }

    public function get($id = 1)
    {
        return $this->find($id);
    }

    public function deleteAllIpAddressForPassedAccessFrequencyTime($access_frequency_time)
    {
        $date = new \DateTime();
        $date->modify("-$access_frequency_time seconds");

        $this->createQueryBuilder('c')
            ->delete()
            ->where('c.access_date < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();
    }
}
