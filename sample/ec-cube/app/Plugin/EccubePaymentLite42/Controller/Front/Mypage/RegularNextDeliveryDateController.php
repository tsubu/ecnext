<?php

namespace Plugin\EccubePaymentLite42\Controller\Front\Mypage;

use Eccube\Controller\AbstractController;
use Plugin\EccubePaymentLite42\Entity\MyPageRegularSetting;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Entity\RegularShipping;
use Plugin\EccubePaymentLite42\Entity\RegularStatus;
use Plugin\EccubePaymentLite42\Form\Type\Front\RegularNextDeliveryDateType;
use Plugin\EccubePaymentLite42\Service\IsActiveRegularService;
use Plugin\EccubePaymentLite42\Service\IsMypageRegularSettingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegularNextDeliveryDateController extends AbstractController
{
    /**
     * @var IsMypageRegularSettingService
     */
    private $isMypageRegularSettingService;
    /**
     * @var IsActiveRegularService
     */
    private $isActiveRegularService;

    public function __construct(
        IsMypageRegularSettingService $isMypageRegularSettingService,
        IsActiveRegularService $isActiveRegularService
    ) {
        $this->isMypageRegularSettingService = $isMypageRegularSettingService;
        $this->isActiveRegularService = $isActiveRegularService;
    }

    /**
     * お届け予定日変更画面.
     *
     * @Route(
     *     "/mypage/eccube_payment_lite/regular/{id}/next_delivery_date",
     *     name="eccube_payment_lite42_mypage_regular_next_delivery_date",
     *     requirements={"id" = "\d+"}
     * )
     * @Template("@EccubePaymentLite42/default/Mypage/regular_next_delivery_date.twig")
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
        if (!$this->isMypageRegularSettingService->handle(MyPageRegularSetting::NEXT_DELIVERY_DATE)) {
            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_list');
        }
        /** @var RegularShipping $RegularShipping */
        $RegularShipping = $RegularOrder->getRegularShippings()->first();
        $form = $this->createForm(RegularNextDeliveryDateType::class, $RegularShipping);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $time_id = isset($form->getData()['shipping_delivery_time']['id']) ? $form->getData()['shipping_delivery_time']['id'] : null;
            $RegularShipping->setTimeId($time_id);
            $this->entityManager->persist($RegularShipping);
            $this->entityManager->flush();
            $this->addWarning('定期商品の次回お届け予定日を変更しました。');

            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_complete', [
                'id' => $RegularOrder->getId(),
            ]);
        }

        return [
            'RegularOrder' => $RegularOrder,
            'form' => $form->createView(),
        ];
    }
}
