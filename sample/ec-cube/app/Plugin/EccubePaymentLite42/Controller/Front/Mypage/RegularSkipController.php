<?php

namespace Plugin\EccubePaymentLite42\Controller\Front\Mypage;

use Eccube\Controller\AbstractController;
use Plugin\EccubePaymentLite42\Entity\MyPageRegularSetting;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Entity\RegularShipping;
use Plugin\EccubePaymentLite42\Entity\RegularStatus;
use Plugin\EccubePaymentLite42\Service\CalculateOneAfterAnotherNextDeliveryDateService;
use Plugin\EccubePaymentLite42\Service\IsActiveRegularService;
use Plugin\EccubePaymentLite42\Service\IsMypageRegularSettingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegularSkipController extends AbstractController
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
     *     "/mypage/eccube_payment_lite/regular/{id}/skip",
     *     name="eccube_payment_lite42_mypage_regular_skip",
     *     requirements={"id" = "\d+"}
     * )
     * @Template("@EccubePaymentLite42/default/Mypage/regular_skip.twig")
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
        if (!$this->isMypageRegularSettingService->handle(MyPageRegularSetting::SKIP_ONCE)) {
            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_list');
        }

        /** @var RegularShipping $RegularShipping */
        $RegularShipping = $RegularOrder->getRegularShippings()->first();
        $oneAfterAnotherNextDeliveryDate = $this
            ->calculateOneAfterAnotherNextDeliveryDateService
            ->calc($RegularOrder->getRegularCycle(), $RegularShipping->getNextDeliveryDate());

        $builder = $this->formFactory->createBuilder();
        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $RegularShipping->setNextDeliveryDate($oneAfterAnotherNextDeliveryDate);
            $RegularOrder->setRegularSkipFlag(1);
            $this->entityManager->persist($RegularOrder);
            $this->entityManager->persist($RegularShipping);
            $this->entityManager->flush();
            $this->addWarning('定期商品のご注文を1回スキップしました。');

            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_complete', [
                'id' => $RegularOrder->getId(),
            ]);
        }

        return [
            'oneAfterAnotherNextDeliveryDate' => $oneAfterAnotherNextDeliveryDate,
            'RegularShipping' => $RegularShipping,
            'form' => $form->createView(),
            'RegularOrder' => $RegularOrder,
        ];
    }
}
