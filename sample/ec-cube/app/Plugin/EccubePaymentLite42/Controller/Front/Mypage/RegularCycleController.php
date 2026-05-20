<?php

namespace Plugin\EccubePaymentLite42\Controller\Front\Mypage;

use Eccube\Controller\AbstractController;
use Plugin\EccubePaymentLite42\Entity\MyPageRegularSetting;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Entity\RegularShipping;
use Plugin\EccubePaymentLite42\Entity\RegularStatus;
use Plugin\EccubePaymentLite42\Form\Type\Front\RegularCycleType;
use Plugin\EccubePaymentLite42\Service\CalculateOneAfterAnotherNextDeliveryDateService;
use Plugin\EccubePaymentLite42\Service\IsActiveRegularService;
use Plugin\EccubePaymentLite42\Service\IsMypageRegularSettingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegularCycleController extends AbstractController
{
    /**
     * @var CalculateOneAfterAnotherNextDeliveryDateService
     */
    private $calculateOneAfterAnotherNextDeliveryDateService;
    /**
     * @var IsMypageRegularSettingService
     */
    private $isMypageRegularSettingService;
    /**
     * @var IsActiveRegularService
     */
    private $isActiveRegularService;

    public function __construct(
        CalculateOneAfterAnotherNextDeliveryDateService $calculateOneAfterAnotherNextDeliveryDateService,
        IsMypageRegularSettingService $isMypageRegularSettingService,
        IsActiveRegularService $isActiveRegularService
    ) {
        $this->calculateOneAfterAnotherNextDeliveryDateService = $calculateOneAfterAnotherNextDeliveryDateService;
        $this->isMypageRegularSettingService = $isMypageRegularSettingService;
        $this->isActiveRegularService = $isActiveRegularService;
    }

    /**
     * @Route(
     *     "/mypage/eccube_payment_lite/regular/{id}/cycle",
     *     name="eccube_payment_lite42_mypage_regular_cycle",
     *     requirements={"id" = "\d+"}
     * )
     * @Template("@EccubePaymentLite42/default/Mypage/regular_cycle.twig")
     */
    public function index(Request $request, RegularOrder $RegularOrder)
    {
        if (!$this->isActiveRegularService->isActive()) {
            return $this->redirectToRoute('mypage');
        }
        if ($RegularOrder->getCustomer()->getId() !== $this->getUser()->getId()) {
            throw new NotFoundHttpException();
        }
        if ($RegularOrder->getRegularStatus()->getId() !== RegularStatus::CONTINUE) {
            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_list');
        }
        if (!$this->isMypageRegularSettingService->handle(MyPageRegularSetting::REGULAR_CYCLE)) {
            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_list');
        }

        $form = $this->createForm(RegularCycleType::class, $RegularOrder);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($RegularOrder);
            $this->entityManager->flush();
            $this->addWarning('定期商品のお届けサイクルを変更しました。');

            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_complete', [
                'id' => $RegularOrder->getId(),
            ]);
        }
        /** @var RegularShipping $RegularShipping */
        $RegularShipping = $RegularOrder->getRegularShippings()->first();
        $oneAfterAnotherNextDeliveryDate = $this
            ->calculateOneAfterAnotherNextDeliveryDateService
            ->calc($RegularOrder->getRegularCycle(), $RegularShipping->getNextDeliveryDate());

        return [
            'oneAfterAnotherNextDeliveryDate' => $oneAfterAnotherNextDeliveryDate,
            'RegularShipping' => $RegularShipping,
            'RegularOrder' => $RegularOrder,
            'form' => $form->createView(),
        ];
    }
}
