<?php

namespace Plugin\EccubePaymentLite42\Controller\Front\Mypage;

use Eccube\Controller\AbstractController;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Service\IsActiveRegularService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegularCompleteController extends AbstractController
{
    /**
     * @var IsActiveRegularService
     */
    private $isActiveRegularService;

    public function __construct(
        IsActiveRegularService $isActiveRegularService
    ) {
        $this->isActiveRegularService = $isActiveRegularService;
    }

    /**
     * /**
     * 完了画面を表示する。
     *
     * @Route(
     *     "/mypage/eccube_payment_lite/regular/{id}/complete",
     *     name="eccube_payment_lite42_mypage_regular_complete",
     *     requirements={"id" = "\d+"}
     * )
     * @Template("@EccubePaymentLite42/default/Mypage/regular_complete.twig")
     *
     * @return array|RedirectResponse
     */
    public function complete(RegularOrder $RegularOrder)
    {
        if (!$this->isActiveRegularService->isActive()) {
            return $this->redirectToRoute('mypage');
        }
        if ($RegularOrder->getCustomer()->getId() !== $this->getUser()->getId()) {
            throw new NotFoundHttpException();
        }

        return [
            'RegularOrder' => $RegularOrder,
        ];
    }
}
