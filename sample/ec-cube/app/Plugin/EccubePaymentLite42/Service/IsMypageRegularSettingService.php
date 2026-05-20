<?php

namespace Plugin\EccubePaymentLite42\Service;

use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Entity\MyPageRegularSetting;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;

class IsMypageRegularSettingService
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

    public function handle(int $id): bool
    {
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);
        /** @var MyPageRegularSetting[] $MypageRegularSettings */
        $MypageRegularSettings = $Config->getMypageRegularSettings()->toArray();
        foreach ($MypageRegularSettings as $MyPageRegularSetting) {
            if ($MyPageRegularSetting->getId() === $id) {
                return true;
            }
        }

        return false;
    }
}
