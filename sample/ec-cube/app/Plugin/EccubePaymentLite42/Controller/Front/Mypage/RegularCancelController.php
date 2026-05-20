<?php

namespace Plugin\EccubePaymentLite42\Controller\Front\Mypage;

use Eccube\Controller\AbstractController;
use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Entity\MyPageRegularSetting;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Entity\RegularShipping;
use Plugin\EccubePaymentLite42\Entity\RegularStatus;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Plugin\EccubePaymentLite42\Repository\RegularOrderRepository;
use Plugin\EccubePaymentLite42\Repository\RegularStatusRepository;
use Plugin\EccubePaymentLite42\Service\IsActiveRegularService;
use Plugin\EccubePaymentLite42\Service\IsMypageRegularSettingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegularCancelController extends AbstractController
{
    /**
     * @var RegularOrderRepository
     */
    private $regularOrderRepository;
    /**
     * @var RegularStatusRepository
     */
    private $regularStatusRepository;
    /**
     * @var IsMypageRegularSettingService
     */
    private $isMypageRegularSettingService;
    /**
     * @var IsActiveRegularService
     */
    private $isActiveRegularService;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(
        RegularOrderRepository $regularOrderRepository,
        RegularStatusRepository $regularStatusRepository,
        IsMypageRegularSettingService $isMypageRegularSettingService,
        IsActiveRegularService $isActiveRegularService,
        ConfigRepository $configRepository
    ) {
        $this->regularOrderRepository = $regularOrderRepository;
        $this->regularStatusRepository = $regularStatusRepository;
        $this->isMypageRegularSettingService = $isMypageRegularSettingService;
        $this->isActiveRegularService = $isActiveRegularService;
        $this->configRepository = $configRepository;
    }

    /**
     * 定期受注解約確認画面を表示する。
     *
     * @Route(
     *     "/mypage/eccube_payment_lite/regular/{id}/cancel",
     *     name="eccube_payment_lite42_mypage_regular_cancel",
     *     requirements={"id" = "\d+"})
     * @Template("@EccubePaymentLite42/default/Mypage/regular_cancel.twig")
     */
    public function cancel(Request $request)
    {
        if (!$this->isActiveRegularService->isActive()) {
            return $this->redirectToRoute('mypage');
        }

        /** @var RegularOrder $RegularOrder */
        $RegularOrder = $this->regularOrderRepository->findOneBy([
            'id' => $request->get('id'),
            'Customer' => $this->getUser(),
        ]);
        if (!$RegularOrder) {
            throw new NotFoundHttpException();
        }

        if ($RegularOrder->getRegularStatus()->getId() !== RegularStatus::CONTINUE && $RegularOrder->getRegularStatus()->getId() !== RegularStatus::SUSPEND) {
            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_list');
        }
        if (!$this->isMypageRegularSettingService->handle(MyPageRegularSetting::CANCELLATION)) {
            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_list');
        }
        $builder = $this->formFactory->createBuilder();
        $form = $builder->getForm();
        $form->handleRequest($request);
        // 解約可能な定期回数かチェック
        if (!$this->isPossibleToCancel($RegularOrder->getRegularOrderCount())) {
            $form->addError(new FormError(''));
            $this->addWarning('定期商品の解約が可能な購入回数に達していないため、解約できません。');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var RegularStatus $RegularStatus */
            $RegularStatus = $this->regularStatusRepository->find(RegularStatus::CANCELLATION);
            /** @var RegularShipping $RegularShipping */
            $RegularShipping = $RegularOrder->getRegularShippings()->first();
            $RegularShipping->setNextDeliveryDate(null);
            $RegularOrder->setRegularStatus($RegularStatus);
            $this->entityManager->persist($RegularOrder);
            $this->entityManager->flush();
            $this->addWarning('定期商品のご注文を解約しました。');

            return $this->redirectToRoute('eccube_payment_lite42_mypage_regular_complete', [
                'id' => $RegularOrder->getId(),
            ]);
        }

        return [
            'form' => $form->createView(),
            'RegularOrder' => $RegularOrder,
        ];
    }

    private function isPossibleToCancel($regularOrderCount): bool
    {
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);
        $regularCancelableCount = $Config->getRegularCancelableCount();
        if ($regularOrderCount < $regularCancelableCount) {
            return false;
        }

        return true;
    }
}
