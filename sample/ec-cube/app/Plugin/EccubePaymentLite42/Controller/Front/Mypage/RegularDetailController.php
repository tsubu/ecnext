<?php

namespace Plugin\EccubePaymentLite42\Controller\Front\Mypage;

use Eccube\Controller\AbstractController;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Repository\RegularOrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegularDetailController extends AbstractController
{
    /**
     * @var RegularOrderRepository
     */
    private $regularOrderRepository;

    public function __construct(
        RegularOrderRepository $regularOrderRepository
    ) {
        $this->regularOrderRepository = $regularOrderRepository;
    }

    /**
     * 定期購入詳細を表示する.
     *
     * @Route(
     *     "/mypage/eccube_payment_lite/regular/{id}/detail",
     *     name="eccube_payment_lite42_mypage_regular_detail",
     *     requirements={"id" = "\d+"}
     * )
     * @Template("@EccubePaymentLite42/default/Mypage/regular_detail.twig")
     */
    public function detail(Request $request)
    {
        /** @var RegularOrder $RegularOrder */
        $RegularOrder = $this->regularOrderRepository->findOneBy([
            'id' => $request->get('id'),
            'Customer' => $this->getUser(),
        ]);

        if (!$RegularOrder) {
            throw new NotFoundHttpException();
        }

        return [
            'RegularOrder' => $RegularOrder,
        ];
    }
}
