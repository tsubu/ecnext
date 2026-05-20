<?php

namespace Plugin\EccubePaymentLite42\Controller\Front\Mypage;

use Eccube\Controller\AbstractController;
use Exception;
use Plugin\EccubePaymentLite42\Entity\RegularCycle;
use Plugin\EccubePaymentLite42\Entity\RegularShipping;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Plugin\EccubePaymentLite42\Repository\RegularCycleRepository;
use Plugin\EccubePaymentLite42\Repository\RegularShippingRepository;
use Plugin\EccubePaymentLite42\Service\CalculateOneAfterAnotherNextDeliveryDateService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryDateController extends AbstractController
{
    /**
     * @var RegularCycleRepository
     */
    private $regularCycleRepository;
    /**
     * @var ConfigRepository
     */
    private $configRepository;
    /**
     * @var RegularShippingRepository
     */
    private $regularShippingRepository;
    /**
     * @var CalculateOneAfterAnotherNextDeliveryDateService
     */
    private $calculateOneAfterAnotherNextDeliveryDateService;

    public function __construct(
        RegularCycleRepository $regularCycleRepository,
        RegularShippingRepository $regularShippingRepository,
        ConfigRepository $configRepository,
        CalculateOneAfterAnotherNextDeliveryDateService $calculateOneAfterAnotherNextDeliveryDateService
    ) {
        $this->regularCycleRepository = $regularCycleRepository;
        $this->regularShippingRepository = $regularShippingRepository;
        $this->configRepository = $configRepository;
        $this->calculateOneAfterAnotherNextDeliveryDateService = $calculateOneAfterAnotherNextDeliveryDateService;
    }

    /**
     * @Route(
     *     "/mypage/eccube_payment_lite/mypage/delivery_date",
     *     name="eccube_payment_lite42_mypage_delivery_date",
     *     methods={"POST"}
     * )
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException();
        }
        $this->isTokenValid();

        $regularCycleId = (int) $request->request->get('regular_cycle_id');
        /** @var RegularShipping $RegularShipping */
        $RegularShipping = $this->regularShippingRepository->find((int) $request->request->get('regular_shipping_id'));
        $nextDeliveryDate = $RegularShipping->getNextDeliveryDate();

        /** @var RegularCycle $RegularCycle */
        $RegularCycle = $this
            ->regularCycleRepository
            ->find($regularCycleId);
        $oneAfterAnotherNextDeliveryDate = $this
            ->calculateOneAfterAnotherNextDeliveryDateService
            ->calc($RegularCycle, clone $nextDeliveryDate);

        return $this->json([
            'next_delivery_date' => $this->getDayWithWeekDay(clone $nextDeliveryDate),
            'one_after_another_next_delivery_date' => $this->getDayWithWeekDay(clone $oneAfterAnotherNextDeliveryDate),
        ]);
    }

    private function getDayWithWeekDay($datetime): string
    {
        $week = [
            '日',
            '月',
            '火',
            '水',
            '木',
            '金',
            '土',
        ];

        return $datetime->format('Y/m/d').'('.$week[(int) $datetime->format('w')].')';
    }
}
