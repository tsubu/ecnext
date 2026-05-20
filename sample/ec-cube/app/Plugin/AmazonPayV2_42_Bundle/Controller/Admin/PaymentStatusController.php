<?php

/*
 * Amazon Pay V2 for EC-CUBE4.2
 * Copyright(c) 2023 EC-CUBE CO.,LTD. all rights reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * This program is not free software.
 * It applies to terms of service.
 *
 */

namespace Plugin\AmazonPayV2_42_Bundle\Controller\Admin;

use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Order;
use Eccube\Repository\PluginRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\PaymentRepository;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Plugin\AmazonPayV2_42_Bundle\Form\Type\Admin\SearchPaymentType;
use Plugin\AmazonPayV2_42_Bundle\Service\Method\AmazonPay;
use Plugin\AmazonPayV2_42_Bundle\Service\AmazonOrderHelper;
use Plugin\AmazonPayV2_42_Bundle\Repository\Master\AmazonStatusRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * 決済状況管理
 */
class PaymentStatusController extends AbstractController
{
    /**
     * @var PaymentStatusRepository
     */
    protected $paymentStatusRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * PaymentController constructor.
     *
     * @param OrderStatusRepository $orderStatusRepository
     */
    public function __construct(
        AmazonStatusRepository $amazonStatusRepository,
        AmazonOrderHelper $amazonOrderHelper,
        PageMaxRepository $pageMaxRepository,
        OrderRepository $orderRepository,
        PaymentRepository $paymentRepository,
        PluginRepository $pluginRepository
    ) {
        $this->amazonStatusRepository = $amazonStatusRepository;
        $this->amazonOrderHelper = $amazonOrderHelper;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
        $this->pluginRepository = $pluginRepository;
    }

    /**
     * 決済状況一覧画面
     *
     * @Route("/%eccube_admin_route%/amazon_pay_v2/payment_status", name="amazon_pay_v2_admin_payment_status")
     * @Route("/%eccube_admin_route%/amazon_pay_v2/payment_status/{page_no}", requirements={"page_no" = "\d+"}, name="amazon_pay_v2_admin_payment_status_pageno")
     * @Template("@AmazonPayV2_42_Bundle/admin/Order/payment_status.twig")
     */
    public function index(Request $request, PaginatorInterface $paginator, $page_no = null)
    {
        $searchForm = $this->createForm(SearchPaymentType::class);

        /**
         * ページの表示件数は, 以下の順に優先される.
         * - リクエストパラメータ
         * - セッション
         * - デフォルト値
         * また, セッションに保存する際は mtb_page_maxと照合し, 一致した場合のみ保存する.
         **/
        $page_count = $this->session->get(
            'amazon_pay_v2.admin.payment_status.search.page_count',
            $this->eccubeConfig->get('eccube_default_page_count')
        );

        $page_count_param = (int) $request->get('page_count');
        $pageMaxis = $this->pageMaxRepository->findAll();

        if ($page_count_param) {
            foreach ($pageMaxis as $pageMax) {
                if ($page_count_param == $pageMax->getName()) {
                    $page_count = $pageMax->getName();
                    $this->session->set('amazon_pay_v2.admin.payment_status.search.page_count', $page_count);
                    break;
                }
            }
        }

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);

            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                /**
                 * 検索が実行された場合は, セッションに検索条件を保存する.
                 * ページ番号は最初のページ番号に初期化する.
                 */
                $page_no = 1;
                $searchData = $searchForm->getData();

                // 検索条件, ページ番号をセッションに保持.
                $this->session->set('amazon_pay_v2.admin.payment_status.search', FormUtil::getViewData($searchForm));
                $this->session->set('amazon_pay_v2.admin.payment_status.search.page_no', $page_no);
            } else {
                // 検索エラーの際は, 詳細検索枠を開いてエラー表示する.
                return [
                    'searchForm' => $searchForm->createView(),
                    'pagination' => [],
                    'pageMaxis' => $pageMaxis,
                    'page_no' => $page_no,
                    'page_count' => $page_count,
                    'has_errors' => true,
                ];
            }
        } else {
            if (null !== $page_no || $request->get('resume')) {
                /*
                 * ページ送りの場合または、他画面から戻ってきた場合は, セッションから検索条件を復旧する.
                 */
                if ($page_no) {
                    // ページ送りで遷移した場合.
                    $this->session->set('amazon_pay_v2.admin.payment_status.search.page_no', (int) $page_no);
                } else {
                    // 他画面から遷移した場合.
                    $page_no = $this->session->get('amazon_pay_v2.admin.payment_status.search.page_no', 1);
                }
                $viewData = $this->session->get('amazon_pay_v2.admin.payment_status.search', []);
                $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
            } else {
                /**
                 * 初期表示の場合.
                 */
                $page_no = 1;
                $searchData = [];

                // セッション中の検索条件, ページ番号を初期化.
                $this->session->set('amazon_pay_v2.admin.payment_status.search', $searchData);
                $this->session->set('amazon_pay_v2.admin.payment_status.search.page_no', $page_no);
            }
        }

        $qb = $this->createQueryBuilder($searchData);
        $pagination = $paginator->paginate(
            $qb,
            $page_no,
            $page_count
        );

        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $page_count,
            'has_errors' => false,
        ];
    }

    /**
     * 一括処理.
     *
     * @Route("/%eccube_admin_route%/amazon_pay_v2/payment_status/request_action/", name="amazon_pay_v2_admin_payment_status_request", methods={"POST"})
     */
    public function requestAction(Request $request)
    {
        $amazon_request = $request->get('amazon_request');

        if (!isset($amazon_request)) {
            throw new BadRequestHttpException();
        }

        $this->isTokenValid();

        $requestOrderId = $request->get('amazon_order_id');
        if (!empty($requestOrderId)) {
            // 個別処理の場合
            $ids = [$requestOrderId];
        } else {
            // 一括処理の場合
            $ids = $request->get($amazon_request . '_id');
        }

        $request_name = $amazon_request == 'capture' ? '売上' : '取消';
        /** @var Order[] $Orders */
        $Orders = $this->orderRepository->findBy(['id' => $ids]);

        foreach ($Orders as $Order) {
            $amazonErr = $this->amazonOrderHelper->adminRequest($amazon_request, $Order);

            if (empty($amazonErr)) {
                $result_message = "■注文番号:" . $Order->getId() . " ： " . $request_name . "処理に成功しました。";

                $this->addSuccess($result_message, 'admin');
            } else {
                $result_message = "■注文番号:" . $Order->getId() . " ： " . $request_name . "処理に失敗しました。" . $amazonErr;

                $this->addError($result_message, 'admin');
            }
        }

        return $this->redirectToRoute('amazon_pay_v2_admin_payment_status_pageno', ['resume' => Constant::ENABLED]);
    }

    private function createQueryBuilder(array $searchData)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('o')
            ->from(Order::class, 'o')
            ->orderBy('o.order_date', 'DESC')
            ->addOrderBy('o.id', 'DESC');

        // CV1が有効化の場合CV1の受注も取得
        $V1Enable_flg = $this->pluginRepository->findOneBy(
            array(
                'code' => 'AmazonPay',
                'enabled' => Constant::ENABLED
            )
        );

        $PaymentV2 = $this->paymentRepository->findOneBy(['method_class' => AmazonPay::class]);
        if (isset($V1Enable_flg)) {
            // AmazonPay CV1とCV2のみ
            $PaymentV1 = $this->paymentRepository->findOneBy(['method_class' => \Plugin\AmazonPay\Service\Method\AmazonPay::class]);
            $qb->andWhere($qb->expr()->orX($qb->expr()->eq('o.Payment', ':PaymentV1'), $qb->expr()->eq('o.Payment', ':PaymentV2')))
                ->setParameter('PaymentV1', $PaymentV1)
                ->setParameter('PaymentV2', $PaymentV2)
                ->andWhere($qb->expr()->orX($qb->expr()->isNotNull('o.AmazonStatus'), $qb->expr()->isNotNull('o.AmazonPayV2AmazonStatus')));
        } else {
            // AmazonPay　V2のみ
            $qb->andWhere('o.Payment = :PaymentV2')
                ->setParameter('PaymentV2', $PaymentV2)
                ->andWhere('o.AmazonPayV2AmazonStatus IS NOT NULL');
        }

        // 決済済みのみ
        $qb->andWhere('o.order_date IS NOT NULL');

        if (!empty($searchData['OrderStatuses']) && count($searchData['OrderStatuses']) > 0) {
            $qb->andWhere($qb->expr()->in('o.OrderStatus', ':OrderStatuses'))
                ->setParameter('OrderStatuses', $searchData['OrderStatuses']);
        }

        if (!empty($searchData['AmazonStatuses']) && count($searchData['AmazonStatuses']) > 0) {
            $qb->andWhere($qb->expr()->in('o.AmazonPayV2AmazonStatus', ':AmazonStatuses'))
                ->setParameter('AmazonStatuses', $searchData['AmazonStatuses']);
        }

        return $qb;
    }
}
