<?php

namespace Plugin\EccubePaymentLite42\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Order;
use Plugin\EccubePaymentLite42\Entity\PaymentStatus;
use Plugin\EccubePaymentLite42\Repository\PaymentStatusRepository;

class UpdatePaymentStatusService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaymentStatusRepository
     */
    private $paymentStatusRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PaymentStatusRepository $paymentStatusRepository
    ) {
        $this->entityManager = $entityManager;
        $this->paymentStatusRepository = $paymentStatusRepository;
    }

    public function handle(Order $Order, int $paymentStatusId)
    {
        /** @var PaymentStatus $PaymentStatus */
        $PaymentStatus = $this->paymentStatusRepository->find($paymentStatusId);
        $Order->setPaymentStatus($PaymentStatus);
        $this->entityManager->persist($Order);
        $this->entityManager->flush();
    }
}
