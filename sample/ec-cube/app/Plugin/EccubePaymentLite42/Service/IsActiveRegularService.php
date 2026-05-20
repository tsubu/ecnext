<?php

namespace Plugin\EccubePaymentLite42\Service;

use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;

class IsActiveRegularService
{
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(
        ConfigRepository $configRepository
    ) {
        $this->configRepository = $configRepository;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);

        return $Config->getRegular();
    }
}
