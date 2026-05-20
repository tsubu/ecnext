<?php

namespace Plugin\EccubePaymentLite42\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Entity\RegularShipping;
use Plugin\EccubePaymentLite42\Entity\RegularStatus;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Plugin\EccubePaymentLite42\Repository\RegularOrderRepository;
use Plugin\EccubePaymentLite42\Repository\RegularStatusRepository;

class UpdateNextShippingDateFromRePaymentService
{
    /**
     * @var ConfigRepository
     */
    private $configRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RegularStatusRepository
     */
    private $regularStatusRepository;
    /**
     * @var RegularOrderRepository
     */
    private $regularOrderRepository;
    /**
     * @var IsActiveRegularService
     */
    private $isActiveRegularService;

    public function __construct(
        ConfigRepository $configRepository,
        RegularStatusRepository $regularStatusRepository,
        RegularOrderRepository $regularOrderRepository,
        EntityManagerInterface $entityManager,
        IsActiveRegularService $isActiveRegularService
    ) {
        $this->configRepository = $configRepository;
        $this->regularStatusRepository = $regularStatusRepository;
        $this->regularOrderRepository = $regularOrderRepository;
        $this->entityManager = $entityManager;
        $this->isActiveRegularService = $isActiveRegularService;
    }

    public function update(Customer $Customer)
    {
        if (!$this->isActiveRegularService->isActive()) {
            return;
        }
        /** @var RegularStatus $RegularStatusPaymentError */
        $RegularStatusPaymentError = $this->regularStatusRepository->find(RegularStatus::WAITING_RE_PAYMENT);
        /** @var RegularOrder[] $RegularOrders */
        $RegularOrders = $this->regularOrderRepository->findBy([
            'Customer' => $Customer,
            'RegularStatus' => $RegularStatusPaymentError,
        ]);
        if (empty($RegularOrders)) {
            return;
        }
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);
        $nextDeliveryDate = new \DateTime('today');
        $nextDeliveryDate->modify('+'.$Config->getNextDeliveryDaysAfterRePayment().' day');
        foreach ($RegularOrders as $RegularOrder) {
            /** @var RegularShipping $RegularShipping */
            $RegularShipping = $RegularOrder->getRegularShippings()->first();
            $RegularShipping->setNextDeliveryDate($nextDeliveryDate);
            $this->entityManager->persist($RegularShipping);
        }
        $this->entityManager->flush();
    }
}
