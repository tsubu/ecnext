<?php

namespace Plugin\EccubePaymentLite42\Service;

use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;

class NextDeliveryDateService
{
    /**
     * @var CalculateNextDeliveryDateService
     */
    private $calculateNextDeliveryDateService;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(
        CalculateNextDeliveryDateService $calculateNextDeliveryDateService,
        ConfigRepository $configRepository
    ) {
        $this->calculateNextDeliveryDateService = $calculateNextDeliveryDateService;
        $this->configRepository = $configRepository;
    }

    public function getStartDateTime(string $day)
    {
        $nextDeliveryDate = new \DateTime('today');
        $nextDeliveryDate->modify('+'.$day.'day');

        return $nextDeliveryDate;
    }

    public function getEndDateTime(string $day, RegularOrder $RegularOrder)
    {
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);

        return $this
            ->calculateNextDeliveryDateService
            ->calc(
                $RegularOrder->getRegularCycle(),
                $Config->getRegularOrderDeadline()
            );
    }
}
