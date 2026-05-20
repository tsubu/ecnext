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

namespace Plugin\AmazonPayV2_42_Bundle\Controller;

use Eccube\Entity\Delivery;
use Eccube\Entity\OrderItem;
use Eccube\Repository\DeliveryRepository;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\TradeLawRepository;
use Plugin\AmazonPayV2_42_Bundle\Repository\ConfigRepository;
use Plugin\AmazonPayV2_42_Bundle\Service\AmazonOrderHelper;
use Plugin\AmazonPayV2_42_Bundle\Service\AmazonRequestService;
use Plugin\AmazonPayV2_42_Bundle\Service\Method\AmazonPay;
use Eccube\Common\EccubeConfig;
use Eccube\Controller\AbstractShoppingController;
use Eccube\Entity\Order;
use Eccube\Entity\Shipping;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Form\Type\Shopping\OrderType;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\PrefRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\PluginRepository;
use Eccube\Service\CartService;
use Eccube\Service\MailService;
use Eccube\Service\OrderHelper;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Service\PurchaseFlow\Processor\AddPointProcessor;
use Eccube\Service\PurchaseFlow\Processor\CustomerPurchaseInfoProcessor;
use Plugin\AmazonPayV2_42_Bundle\Exception\AmazonPaymentException;
use Plugin\AmazonPayV2_42_Bundle\Amazon\Pay\API\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as EncoderFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\LockMode;

class AmazonShoppingController extends AbstractShoppingController
{
    /**
     * @var string プロファイル情報キー
     */
    private $sessionAmazonProfileKey = 'amazon_pay_v2.profile';

    /**
     * @var string プロファイル情報キー
     */
    private $sessionAmazonCheckoutSessionIdKey = 'amazon_pay_v2.checkout_session_id';

    /**
     * @var string 会員登録情報
     */
    private $sessionAmazonCustomerParamKey = 'amazon_pay_v2.customer_regist_v2';

    /**
     * @var string 会員登録エラー情報
     */
    private $sessionAmazonCustomerErrorKey = 'amazon_pay_v2.customer_regist_v2_error';

    /**
     * @var string shippingを更新するか
     */
    private $sessionIsShippingRefresh = 'amazon_pay_v2.is_shipping_refresh';

    /**
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     * @var CartService
     */
    protected $cartService;

    protected $amazonOrderHelper;

    protected $addPointProcessor;

    protected $customerPurchaseInfoProcessor;

    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * @var TradeLawRepository
     */
    protected TradeLawRepository $tradeLawRepository;

    public function __construct(
        EccubeConfig $eccubeConfig,
        PurchaseFlow $cartPurchaseFlow,
        CartService $cartService,
        MailService $mailService,
        OrderHelper $orderHelper,
        CustomerRepository $customerRepository,
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        PrefRepository $prefRepository,
        ProductClassRepository $productClassRepository,
        PluginRepository $pluginRepository,
        ConfigRepository $configRepository,
        AmazonOrderHelper $amazonOrderHelper,
        AmazonRequestService $amazonRequestService,
        ValidatorInterface $validator,
        EncoderFactoryInterface $encoderFactory,
        TokenStorageInterface $tokenStorage,
        DeliveryRepository $deliveryRepository,
        AddPointProcessor $addPointProcessor,
        CustomerPurchaseInfoProcessor $customerPurchaseInfoProcessor,
        TradeLawRepository $tradeLawRepository,
        ContainerInterface $serviceContainer,
        AmazonPay $amazonPay
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->purchaseFlow = $cartPurchaseFlow;
        $this->cartService = $cartService;
        $this->mailService = $mailService;
        $this->orderHelper = $orderHelper;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->prefRepository = $prefRepository;
        $this->productClassRepository = $productClassRepository;
        $this->pluginRepository = $pluginRepository;
        $this->Config = $configRepository->get();
        $this->amazonOrderHelper = $amazonOrderHelper;
        $this->amazonRequestService = $amazonRequestService;
        $this->validator = $validator;
        $this->encoderFactory = $encoderFactory;
        $this->tokenStorage = $tokenStorage;
        $this->deliveryRepository = $deliveryRepository;
        $this->addPointProcessor = $addPointProcessor;
        $this->customerPurchaseInfoProcessor = $customerPurchaseInfoProcessor;
        $this->tradeLawRepository = $tradeLawRepository;
        $this->serviceContainer = $serviceContainer;
        $this->amazonPay = $amazonPay;
    }

    /**
     * @Route("/shopping/amazon_pay", name="amazon_pay_shopping")
     * @Template("Shopping/index.twig")
     *
     * @param Request $request
     */
    public function index(Request $request, PurchaseFlow $cartPurchaseFlow)
    {
        logs('amazon_pay_v2')->info('AmazonShopping::index start.');

        // カートチェック
        $Cart = $this->cartService->getCart();
        if (!($Cart && $this->orderHelper->verifyCart($Cart))) {
            logs('amazon_pay_v2')->info('[注文手続] カートが購入フローへ遷移できない状態のため, カート画面に遷移します.');

            return $this->redirectToRoute('cart');
        }

        // CheckoutSessionIdよりデータ取得
        $amazonCheckoutSessionId = $this->session->get($this->sessionAmazonCheckoutSessionIdKey);

        $checkoutSession = $this->amazonRequestService->getCheckoutSession($amazonCheckoutSessionId);

        if($checkoutSession && $checkoutSession->statusDetails->state !== 'Open') {
            logs('amazon_pay_v2')->info('[注文手続] CheckoutSessionがOpenで無い為決済処理を中断します.', ['CheckoutSessionId => $amazonCheckoutSessionId']);

            $this->session->remove($this->sessionAmazonCheckoutSessionIdKey);

            return $this->redirectToRoute('shopping_error');
        }

        // 受注情報を初期化
        logs('amazon_pay_v2')->info('[注文手続] 受注の初期化処理を開始します.');
        $Customer = $this->getUser() ? $this->getUser() : $this->amazonOrderHelper->getOrderer($checkoutSession->shippingAddress);

        // 非会員購入時のみ会員情報をセッションに入れる
        if(!$this->isGranted('ROLE_USER')){
            $this->session->set(OrderHelper::SESSION_NON_MEMBER, $Customer);
        }

        $initOrderFlg = false;
        if ($Order = $this->orderHelper->getPurchaseProcessingOrder($Cart->getPreOrderId())) {
            // 複数配送の場合はカートセッションのpre_order_idにnullをセット
            if ($Order->isMultiple()) {
                $Cart->setPreOrderId(null);
            }
        } else {
            // initializeOrderの前にOrderの存在をチェック
            $initOrderFlg = true;
        }

        $Order = $this->orderHelper->initializeOrder($Cart, $Customer);

        $Shipping = $Order->getShippings()->first();
        $AmazonDefaultDelivery = $this->getAmazonPayDefaultDelivery($Shipping);

        if ($AmazonDefaultDelivery === false) {
            // 配送方法が無かった場合エラーを出す。
            $this->addError('Amazon Payでご利用できる配送方法が存在しません。');
        }

        if ($initOrderFlg && $AmazonDefaultDelivery) {
            // 初期選択配送種別でAmazonPayが使えない場合、初回表示時にその配送種別の送料で計算されるため計算の前に配送方法を変更する
            $Shipping->setDelivery($AmazonDefaultDelivery);
            $Shipping->setShippingDeliveryName($AmazonDefaultDelivery->getName());

            $this->entityManager->flush();
        }

        $Order = $this->amazonOrderHelper->initializeAmazonOrder($Order, $Customer);

        // 配送先の更新
        if ($this->session->get($this->sessionIsShippingRefresh)) {
            $Shippings = $Order->getShippings();
            // EC-CUBEに対応付け
            $this->amazonOrderHelper->convert($Shippings->first(), $checkoutSession->shippingAddress);
            // Amazonからは会社名が連携されないため会社名をクリアする
            $Shippings->first()->setCompanyName('');
            // 受注関連情報を最新状態に更新
            $this->entityManager->flush();

            $this->session->remove($this->sessionIsShippingRefresh);
        }

        // 集計処理
        logs('amazon_pay_v2')->info('[注文手続] 集計処理を開始します.', [$Order->getId()]);
        $flowResult = $this->executePurchaseFlow($Order, false);
        $this->entityManager->flush();

        if ($flowResult->hasError()) {
            logs('amazon_pay_v2')->info('[注文手続] Errorが発生したため購入エラー画面へ遷移します.', [$flowResult->getErrors()]);

            return $this->redirectToRoute('shopping_error');
        }

        if ($flowResult->hasWarning()) {
            logs('amazon_pay_v2')->info('[注文手続] Warningが発生しました.', [$flowResult->getWarning()]);

            // 受注明細と同期をとるため, CartPurchaseFlowを実行する
            $cartPurchaseFlow->validate($Cart, new PurchaseContext());
            $this->cartService->save();
        }

        // 会員登録フォームの初期値
        if ($amazonCustomerParam = $this->session->get($this->sessionAmazonCustomerParamKey)) {
            $arrAmazonCustomerParam = unserialize($amazonCustomerParam);

            if (empty($arrAmazonCustomerParam['customer_regist_v2'])) {
                $arrAmazonCustomerParam['customer_regist_v2'] = false;
            }

            if (empty($arrAmazonCustomerParam['mail_magazine'])) {
                $arrAmazonCustomerParam['mail_magazine'] = false;
            }
        } else {
            // 初回遷移時
            $arrAmazonCustomerParam = [
                'customer_regist_v2' => true,
                'mail_magazine' => true,
                'login_check_v2' => 'regist',
                'amazon_login_email_v2' => null,
                'amazon_login_password_v2' => null
            ];
        }

        $form = $this->createForm(OrderType::class, $Order);

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->setAmazonCustomerData($form, $arrAmazonCustomerParam);
        }
        // 会員登録エラーの有無をチェック
        if ($amazonCustomerError = $this->session->get($this->sessionAmazonCustomerErrorKey)) {
            // エラー情報をセット
            $arrAmazonCustomerError = unserialize($amazonCustomerError);

            foreach ($arrAmazonCustomerError as $key => $val) {
                $form[$key]->addError(new FormError($val));
            }

            $this->session->set($this->sessionAmazonCustomerErrorKey, null);
        }

        // 特定商取引法に基づく表記
        $activeTradeLaws = $this->tradeLawRepository->findBy(['displayOrderScreen' => true], ['sortNo' => 'ASC']);

        $form->handleRequest($request);

        logs('amazon_pay_v2')->info('AmazonShopping::index end.');
        return [
            'form' => $form->createView(),
            'Order' => $Order,
            'AmazonCustomer' => $arrAmazonCustomerParam,
            'AmazonPaymentDescriptor' => $checkoutSession->paymentPreferences[0]->paymentDescriptor,
            'AmazonShippingAddress' => $checkoutSession->shippingAddress,
            'activeTradeLaws' => $activeTradeLaws,
        ];
    }

    /**
     * ご注文内容のご確認
     *
     * @Route("/shopping/amazon_pay/confirm", name="amazon_pay_shopping_confirm", methods={"POST"})
     * @Template("Shopping/confirm.twig")
     */
    public function confirm(Request $request)
    {
        logs('amazon_pay_v2')->info('AmazonShopping::confirm start.');

        // カートチェック
        $Cart = $this->cartService->getCart();
        if (!($Cart && $this->orderHelper->verifyCart($Cart))) {
            logs('amazon_pay_v2')->info('[注文手続] カートが購入フローへ遷移できない状態のため, カート画面に遷移します.');
            return $this->redirectToRoute('cart');
        }

        // 受注の存在チェック.
        $preOrderId = $this->cartService->getPreOrderId();
        $Order = $this->orderHelper->getPurchaseProcessingOrder($preOrderId);
        if (!$Order) {
            logs('amazon_pay_v2')->info('[リダイレクト] 購入処理中の受注が存在しません.');

            return $this->redirectToRoute('shopping_error');
        }

        $arrAmazonCustomerParam = $this->getAmazonCustomerParam($request);

        $this->session->set($this->sessionAmazonCustomerParamKey, serialize($arrAmazonCustomerParam));

        // フォームの生成
        $form = $this->createForm(OrderType::class, $Order);

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->setAmazonCustomerData($form, $arrAmazonCustomerParam);
        }

        $form->handleRequest($request);

        if ($arrAmazonCustomerError = $this->checkAmazonCustomerError($request, $form, $Order)) {
            $this->session->set($this->sessionAmazonCustomerErrorKey, serialize($arrAmazonCustomerError));

            return $this->redirectToRoute('amazon_pay_shopping');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            logs('amazon_pay_v2')->info('[注文確認] 集計処理を開始します.', [$Order->getId()]);
            $response = $this->executePurchaseFlow($Order);
            $this->entityManager->flush();

            if ($response) {
                return $this->redirectToRoute('amazon_pay_shopping');
            }

            logs('amazon_pay_v2')->info('[注文確認] PaymentMethod::verifyを実行します.', [$Order->getPayment()->getMethodClass()]);
            $paymentMethod = $this->createPaymentMethod($Order, $form);
            $PaymentResult = $paymentMethod->verify();

            if ($PaymentResult) {
                if (!$PaymentResult->isSuccess()) {
                    $this->entityManager->rollback();
                    foreach ($PaymentResult->getErrors() as $error) {
                        $this->addError($error);
                    }

                    logs('amazon_pay_v2')->info('[注文確認] PaymentMethod::verifyのエラーのため, 注文手続き画面へ遷移します.', [$PaymentResult->getErrors()]);

                    return $this->redirectToRoute('amazon_pay_shopping');
                }

                $response = $PaymentResult->getResponse();
                if ($response && ($response->isRedirection() || $response->getContent())) {
                    $this->entityManager->flush();

                    logs('amazon_pay_v2')->info('[注文確認] PaymentMethod::verifyが指定したレスポンスを表示します.');

                    return $response;
                }
            }

            $this->entityManager->flush();            

            // 特定商取引法に基づく表記
            $activeTradeLaws = $this->tradeLawRepository->findBy(['displayOrderScreen' => true], ['sortNo' => 'ASC']);

            logs('amazon_pay_v2')->info('[注文確認] 注文確認画面を表示します.');

            return [
                'form' => $form->createView(),
                'Order' => $Order,
                'activeTradeLaws' => $activeTradeLaws,
            ];
        }

        logs('amazon_pay_v2')->info('[注文確認] フォームエラーのため, 注文手続画面へ遷移します', [$Order->getId()]);

        return $this->redirectToRoute('amazon_pay_shopping', ['request' => $request], 307);
    }

    /**
     * 購入処理
     *
     * @Route("/shopping/amazon_pay/checkout", name="amazon_pay_shopping_checkout", methods={"POST"})
     * @Template("Shopping/index.twig")
     */
    public function checkout(Request $request)
    {
        logs('amazon_pay_v2')->info('AmazonShopping::order start.');

        // カートチェック.
        $Cart = $this->cartService->getCart();
        if (!($Cart && $this->orderHelper->verifyCart($Cart))) {
            logs('amazon_pay_v2')->info('[注文手続] カートが購入フローへ遷移できない状態のため, カート画面に遷移します.');

            return $this->redirectToRoute('cart');
        }

        // 受注の存在チェック.
        $preOrderId = $this->cartService->getPreOrderId();
        $Order = $this->orderHelper->getPurchaseProcessingOrder($preOrderId);
        if (!$Order) {
            logs('amazon_pay_v2')->info('[リダイレクト] 購入処理中の受注が存在しません.');

            return $this->redirectToRoute('shopping_error');
        }

        // CheckoutSessionIdよりデータ取得
        $amazonCheckoutSessionId = $this->session->get($this->sessionAmazonCheckoutSessionIdKey);

        $checkoutSession = $this->amazonRequestService->getCheckoutSession($amazonCheckoutSessionId);

        // 配送先のチェック.
        $shippingDifference = $this->checkShippingDifference($Order, $checkoutSession->shippingAddress);
        if ($shippingDifference) {
            $this->session->set($this->sessionIsShippingRefresh, true);
            return $this->redirectToRoute('amazon_pay_shopping');
        }

        // 配送先都道府県が不正な場合エラー
        if (!$this->amazonOrderHelper->checkShippingPref($checkoutSession->shippingAddress)) {
            $this->addError('amazon_pay_v2.front.shopping.undefined_pref_error');
            logs('amazon_pay_v2')->error('[注文手続] 都道府県割当エラー', [$Order->getId()]);

            return $this->redirectToRoute('shopping_error');
        }

        if($checkoutSession && $checkoutSession->statusDetails->state !== 'Open') {
            logs('amazon_pay_v2')->info('[注文手続] CheckoutSessionがOpenで無い為決済処理を中断します.', ['CheckoutSessionId => $amazonCheckoutSessionId']);

            $this->session->remove($this->sessionAmazonCheckoutSessionIdKey);

            return $this->redirectToRoute('shopping_error');
        }

        // form作成
        if ($this->Config->getUseConfirmPage() == $this->eccubeConfig['amazon_pay_v2']['toggle']['off']) {
            $arrAmazonCustomerParam = $this->getAmazonCustomerParam($request);
            $this->session->set($this->sessionAmazonCustomerParamKey, serialize($arrAmazonCustomerParam));

            // フォームの生成
            $form = $this->createForm(OrderType::class, $Order);
        } else {
            $amazonCustomerParam = $this->session->get($this->sessionAmazonCustomerParamKey);
            $arrAmazonCustomerParam = unserialize($amazonCustomerParam);

            $form = $this->createForm(OrderType::class, $Order, [
                // 確認画面から注文処理へ遷移する場合は, Orderエンティティで値を引き回すためフォーム項目の定義をスキップする.
                'skip_add_form' => true,
            ]);
        }
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->setAmazonCustomerData($form, $arrAmazonCustomerParam);
        }
        $form->handleRequest($request);

        if ($this->Config->getUseConfirmPage() == $this->eccubeConfig['amazon_pay_v2']['toggle']['on']) { } else {
            if ($arrAmazonCustomerError = $this->checkAmazonCustomerError($request, $form, $Order)) {
                $this->session->set($this->sessionAmazonCustomerErrorKey, serialize($arrAmazonCustomerError));

                return $this->redirectToRoute('amazon_pay_shopping');
            }
        }

        // 受注処理
        if ($form->isSubmitted() && $form->isValid()) {
            // NOTE: AmazonPayAPI仕様上、0円の決済は許容しない.
            if ($Order->getPaymentTotal() == 0) {
                $errMessage = AmazonPaymentException::$errorMessages[AmazonPaymentException::ZERO_PAYMENT];
                logs('amazon_pay_v2')->error('orderId: ' . $Order->getId() . ' message: ' . $errMessage);
                $this->addError($errMessage);

                return $this->redirectToRoute('amazon_pay_shopping');
            }
            
            $checkoutSession = $this->amazonRequestService->updateCheckoutSession($Order, $amazonCheckoutSessionId);

            if (isset($checkoutSession->reasonCode)) {
                logs('amazon_pay_v2')->error('reasonCode: ' . $checkoutSession->reasonCode . ' message: ' . $checkoutSession->message);
                $this->addError('予期しないエラーが発生しました。');

                return $this->redirectToRoute('amazon_pay_shopping');
            }

            //applyする
            logs('amazon_pay_v2')->info('購入処理開始', [$Order->getId()]);

            $response = $this->executePurchaseFlow($Order);
            $this->entityManager->flush();

            if ($response) {
                return $this->redirectToRoute('amazon_pay_shopping');
            }

            // AmazonPay
            $paymentMethod = $this->createPaymentMethod($Order, $form);

            // 在庫を確保.
            logs('amazon_pay_v2')->info('[注文処理] PaymentMethod::applyを実行します.');
            if ($response = $paymentMethod->apply()) {
                return $response;
            }

            // IPNで必要な情報をOrderに保存する
            $session_temp = [
                'IS_AUTHENTICATED_FULLY' => $this->isGranted('IS_AUTHENTICATED_FULLY'),
                $this->sessionAmazonCheckoutSessionIdKey => $amazonCheckoutSessionId,
                $this->sessionAmazonProfileKey => unserialize($this->session->get($this->sessionAmazonProfileKey)),
                $this->sessionAmazonCustomerParamKey => unserialize($this->session->get($this->sessionAmazonCustomerParamKey)),
            ];
            $Order->setAmazonPayV2SessionTemp(serialize($session_temp));

            $this->entityManager->flush();

            return new RedirectResponse($checkoutSession->webCheckoutDetails->amazonPayRedirectUrl);
        }

        logs('amazon_pay_v2')->info('購入チェックエラー', [$Order->getId()]);

        return $this->redirectToRoute('amazon_pay_shopping', ['request' => $request], 307);
    }

    /**
     * 結果受取
     *
     * @Route("/shopping/amazon_pay/checkout_result", name="amazon_pay_shopping_checkout_result")
     */
    public function checkoutResult(Request $request)
    {

        // 受注の存在チェック.
        $preOrderId = $this->cartService->getPreOrderId();
        $Order = $this->orderRepository->findOneBy([
            'pre_order_id' => $preOrderId,
        ]);

        if (!$Order) {
            logs('amazon_pay_v2')->info('[リダイレクト] 受注が存在しません.');

            return $this->redirectToRoute('shopping_error');
        }

        if ($Order->getOrderStatus() == $this->orderStatusRepository->find(OrderStatus::NEW)) {
            logs('amazon_pay_v2')->info('[注文処理] IPNにより注文処理完了済.', [$Order->getId()]);
            $result = $this->abortCheckoutResult($Order);
            $this->entityManager->flush();
            return $result;
        } elseif ($Order->getOrderStatus() != $this->orderStatusRepository->find(OrderStatus::PENDING)) {
            logs('amazon_pay_v2')->info('[リダイレクト] 決済処理中の受注が存在しません.');

            return $this->redirectToRoute('shopping_error');
        }

        $amazonCheckoutSessionId = $request->get('amazonCheckoutSessionId');

        $amazonCustomerParam = $this->session->get($this->sessionAmazonCustomerParamKey);
        $arrAmazonCustomerParam = unserialize($amazonCustomerParam);

        try {
              logs('amazon_pay_v2')->info('決済完了レスポンス受取', [$Order->getId()]);

            // フォームの生成
            $form = $this->createForm(OrderType::class, $Order);

            // AmazonPay
            $paymentMethod = $this->createPaymentMethod($Order, $form, $amazonCheckoutSessionId);

            // ここで最新の受注情報を取得し、
            // 取得していた受注情報と更新日時に違いがあるなら
            // 他スレッドからの更新があったものとして終了する
            $this->entityManager->beginTransaction();
            $LockedOrder = $this->orderRepository->find($Order['id'], LockMode::PESSIMISTIC_WRITE);
            // 重複処理調査用のログ出力
            logs('amazon_pay_v2')->info('$Order[update_date] is ' . $Order['update_date']->format('Y-m-d H:i:s.u') . ', $LockedOrder[update_date] is ' . $LockedOrder['update_date']->format('Y-m-d H:i:s.u'));
            if ($Order['update_date'] != $LockedOrder['update_date']) {
                logs('amazon_pay_v2')->info('[注文処理] 受注情報に更新がありました。処理を中断します。');
                $result = $this->abortCheckoutResult($Order);
                $this->entityManager->flush();
                $this->entityManager->commit();
                return $result;
            }

            // 決済実行
            logs('amazon_pay_v2')->info('[注文処理] PaymentMethod::checkoutを実行します.');
            if ($response = $this->executeCheckout($paymentMethod, $Order)) {
                return $response;
            }

            $profile = unserialize($this->session->get($this->sessionAmazonProfileKey));
            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $Customer = $this->getUser();
                $Customers = $this->customerRepository->getNonWithdrawingCustomers(['v2_amazon_user_id' => $profile->buyerId]);
                if (!$Customer->getV2AmazonUserId() && empty($Customers[0])) {
                    $Customer->setV2AmazonUserId($profile->buyerId);
                }
            } else {
                if (empty($arrAmazonCustomerParam['login_check_v2']) || $arrAmazonCustomerParam['login_check_v2'] == 'regist') {
                    if ($arrAmazonCustomerParam['customer_regist_v2']) {
                        $url = $this->generateUrl('mypage_login', [], UrlGeneratorInterface::ABSOLUTE_URL);
                        $dtbCustomer = $this->customerRepository->getNonWithdrawingCustomers(['email' => $Order->getEmail()]);
                        $amazonDtbCustomer = $this->customerRepository->getNonWithdrawingCustomers(['v2_amazon_user_id' => $profile->buyerId]);
                        if (
                            $dtbCustomer ||
                            $amazonDtbCustomer
                        ) {
                            // 登録済み
                            if ($amazonDtbCustomer) {
                                $mail = $amazonDtbCustomer[0]->getEmail();
                            } else {
                                $mail = $dtbCustomer[0]->getEmail();
                            }
                            $mail_message = <<<__EOS__
************************************************
　会員登録情報
************************************************
マイページURL：{$url}
※会員登録済みです。メールアドレスは{$mail}です。

__EOS__;
                        } else {
                            // 会員登録処理
                            $password = $this->amazonOrderHelper->registCustomer($Order, $arrAmazonCustomerParam['mail_magazine']);

                            $Customer = $this->getUser();
                            $Order->setCustomer($Customer);
                            $this->customerPurchaseInfoProcessor->commit($Order, new PurchaseContext());

                            $mail = $Customer->getEmail();
                            $mail_message = <<<__EOS__
************************************************
　会員登録情報
************************************************
マイページURL：{$url}
ログインメールアドレス：{$mail}
初期パスワード：{$password}

__EOS__;
                        }

                        $Order->setCompleteMailMessage($mail_message);
                    }
                } else if ($arrAmazonCustomerParam['login_check_v2'] == 'login') {
                    // ログイン処理
                    if ($this->setLogin($request, $Order, $arrAmazonCustomerParam['amazon_login_email_v2'])) {
                        // ログイン処理が正常終了した場合、会員情報にbuyerIdを紐付け
                        $Customer = $Order->getCustomer();
                        $Customers = $this->customerRepository->getNonWithdrawingCustomers(['v2_amazon_user_id' => $profile->buyerId]);
                        if (!$Customer->getV2AmazonUserId() && empty($Customers[0])) {
                            $Customer->setV2AmazonUserId($profile->buyerId);
                        }
                    }
                    
                }
            }

            // 加算ポイントを算出する
            logs('amazon_pay_v2')->info('AddPointProcessorを実行します.', [$Order->getId()]);
            $this->addPointProcessor->validate($Order, new PurchaseContext());

            $this->entityManager->flush();
            $this->entityManager->commit();

            logs('amazon_pay_v2')->info('[注文処理] 注文処理が完了しました.', [$Order->getId()]);

            logs('amazon_pay_v2')->info('購入処理完了', [$Order->getId()]);
        } catch (ShoppingException $e) {
            $this->addError($e->getMessage());
            logs('amazon_pay_v2')->error('購入エラー', [$e->getMessage()]);

            $this->purchaseFlow->rollback($Order, new PurchaseContext());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $this->redirectToRoute('shopping_error');
        } catch (AmazonPaymentException $e) {
            $this->addError($e->getMessage());
            logs('amazon_pay_v2')->error($e->getMessage(), [$Order->getId()]);

            $this->purchaseFlow->rollback($Order, new PurchaseContext());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $this->redirectToRoute('shopping_error');
        } catch (\Exception $e) {
            $this->addError('front.shopping.system_error');
            logs('amazon_pay_v2')->error('予期しないエラー', [get_class($e),$e->getMessage()]);

            $this->purchaseFlow->rollback($Order, new PurchaseContext());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $this->redirectToRoute('shopping_error');
        }

        logs('amazon_pay_v2')->info('AmazonShopping::complete_order end.');

        // 受注IDをセッションにセット
        $this->session->set(OrderHelper::SESSION_ORDER_ID, $Order->getId());

        // メール送信
        logs('amazon_pay_v2')->info('[注文処理] 注文メールの送信を行います.', [$Order->getId()]);

        // 特記事項を設定
        if (!is_null($this->Config->getMailNotices())) {
            $Order->appendCompleteMailMessage("特記事項：" . $this->Config->getMailNotices());
        }

        $this->mailService->sendOrderMail($Order);
        $this->entityManager->flush();

        // カート削除
        logs('amazon_pay_v2')->info('[注文処理] カートをクリアします.', [$Order->getId()]);
        $this->cartService->clear();

        // セッション情報を空にする
        $Order->setAmazonPayV2SessionTemp(null);
        $this->entityManager->flush();

        $this->session->set($this->sessionAmazonCheckoutSessionIdKey, null);
        $this->session->set($this->sessionAmazonCustomerParamKey, null);
        $this->session->set($this->sessionAmazonCustomerErrorKey, null);

        logs('amazon_pay_v2')->info('[注文処理] 注文処理が完了しました. 購入完了画面へ遷移します.', [$Order->getId()]);

        return $this->redirectToRoute('shopping_complete');
    }

    function abortCheckoutResult($Order) {

        // 受注IDをセッションにセット
        $this->session->set(OrderHelper::SESSION_ORDER_ID, $Order->getId());

        // カート削除
        logs('amazon_pay_v2')->info('[注文処理] カートをクリアします.', [$Order->getId()]);
        $this->cartService->clear();

        // セッション情報を空にする
        $Order->setAmazonPayV2SessionTemp(null);

        $this->session->set($this->sessionAmazonCheckoutSessionIdKey, null);
        $this->session->set($this->sessionAmazonCustomerParamKey, null);
        $this->session->set($this->sessionAmazonCustomerErrorKey, null);

        logs('amazon_pay_v2')->info('[注文処理] 購入完了画面へ遷移します.', [$Order->getId()]);

        return $this->redirectToRoute('shopping_complete');
    }

    /**
     * 購入確認画面から, 他の画面へのリダイレクト.
     * 配送業者や支払方法、お問い合わせ情報をDBに保持してから遷移する.
     *
     * @Route("/shopping/amazon_pay/redirect_to", name="amazon_pay_shopping_redirect_to", methods={"POST"})
     * @Template("Shopping/index.twig")
     */
    public function redirectTo(Request $request, RouterInterface $router)
    {
        // 受注の存在チェック.
        $preOrderId = $this->cartService->getPreOrderId();
        $Order = $this->orderHelper->getPurchaseProcessingOrder($preOrderId);
        if (!$Order) {
            logs('amazon_pay_v2')->info('[リダイレクト] 購入処理中の受注が存在しません.');

            return $this->redirectToRoute('shopping_error');
        }

        $form = $this->createForm(OrderType::class, $Order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            logs('amazon_pay_v2')->info('[リダイレクト] 集計処理を開始します.', [$Order->getId()]);
            $flowResult = $this->executePurchaseFlow($Order, false);
            $this->entityManager->flush();

            if ($flowResult->hasError()) {
                logs('amazon_pay_v2')->info('Errorが発生したため購入エラー画面へ遷移します.', [$flowResult->getErrors()]);

                return $this->redirectToRoute('shopping_error');
            }

            if ($flowResult->hasWarning()) {
                logs('amazon_pay_v2')->info('Warningが発生したため注文手続き画面へ遷移します.', [$flowResult->getWarning()]);

                return $this->redirectToRoute('amazon_pay_shopping');
            }

            $redirectTo = $form['redirect_to']->getData();
            if (empty($redirectTo)) {

                logs('amazon_pay_v2')->info('[リダイレクト] リダイレクト先未指定のため注文手続き画面へ遷移します.');

                return $this->redirectToRoute('amazon_pay_shopping');
            }

            try {
                // リダイレクト先のチェック.
                $pattern = '/^' . preg_quote($request->getBasePath(), '/') . '/';

                $redirectTo = preg_replace($pattern, '', $redirectTo);
                $result = $router->match($redirectTo);

                return $this->forwardToRoute($result['_route']);
            } catch (\Exception $e) {
                logs('amazon_pay_v2')->info('[リダイレクト] URLの形式が不正です', [$redirectTo, $e->getMessage()]);

                return $this->redirectToRoute('shopping_error');
            }
        }

        logs('amazon_pay_v2')->info('[リダイレクト] フォームエラーのため, 注文手続き画面を表示します.', [$Order->getId()]);

        return $this->redirectToRoute('amazon_pay_shopping', ['request' => $request], 307);
    }

    /**
     * API通信によって配送業者や支払方法、お問い合わせ情報をDBに保存する.
     *
     * @Route("/shopping/amazon_pay/order_save", name="amazon_pay_shopping_order_save", methods={"POST", "GET"})
     */
    public function orderSave(Request $request)
    {
        // API通信のみ受け付ける
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException();
        }

        // 受注の存在チェック.
        $preOrderId = $this->cartService->getPreOrderId();
        $Order = $this->orderHelper->getPurchaseProcessingOrder($preOrderId);
        if (!$Order) {
            logs('amazon_pay_v2')->info('購入処理中の受注が存在しません.');
            return $this->json([
                'error' => 'OrderNotFound'
            ], 500);
        }

        $form = $this->createForm(OrderType::class, $Order);
        $form->handleRequest($request);

        $arrAmazonCustomerParam = $this->getAmazonCustomerParam($request);
        $this->session->set($this->sessionAmazonCustomerParamKey, serialize($arrAmazonCustomerParam));

        if ($form->isSubmitted() && $form->isValid()) {
            logs('amazon_pay_v2')->info('集計処理を開始します.', [$Order->getId()]);
            $flowResult = $this->executePurchaseFlow($Order, false);
            $this->entityManager->flush();

            if ($flowResult->hasError()) {
                logs('amazon_pay_v2')->info('executePurchaseFlowでErrorが発生しました.', [$flowResult->getErrors()]);

                return  $this->json([
                    'error' => 'executePurchaseFlow::Error'
                ], 500);
            }

            if ($flowResult->hasWarning()) {
                logs('amazon_pay_v2')->info('executePurchaseFlowでWarningが発生しました.', [$flowResult->getWarning()]);

                return  $this->json([
                    'error' => 'executePurchaseFlow::Warning'
                ], 500);
            }

            return $this->json([]);
        }

        logs('amazon_pay_v2')->info('フォームエラーが発生しました.');

        return  $this->json([
            'error' => 'validateError'
        ], 500);
    }

    private function createPaymentMethod(Order $Order, FormInterface $form, $amazonCheckoutSessionId = null)
    {
        $PaymentMethod = $this->amazonPay;
        $PaymentMethod->setOrder($Order);
        $PaymentMethod->setFormType($form);

        if (!is_null($amazonCheckoutSessionId)) {
            $PaymentMethod->setAmazonCheckoutSessionId($amazonCheckoutSessionId);
        }

        return $PaymentMethod;
    }

    /**
     * PaymentMethod::checkoutを実行する.
     *
     * @param PaymentMethodInterface $paymentMethod
     * @param Order $Order
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function executeCheckout(AmazonPay $paymentMethod, Order $Order)
    {
        $PaymentResult = $paymentMethod->checkout();
        $response = $PaymentResult->getResponse();
        // PaymentResultがresponseを保持している場合はresponseを返す
        if ($response && ($response->isRedirection() || $response->getContent())) {
            $this->entityManager->flush();
            logs('amazon_pay_v2')->info('[注文処理] PaymentMethod::checkoutが指定したレスポンスを表示します.');

            return $response;
        }

        // エラー時はロールバックして購入エラーとする.
        if (!$PaymentResult->isSuccess()) {
            //$this->entityManager->rollback();
            $this->purchaseFlow->rollback($Order, new PurchaseContext());
            foreach ($PaymentResult->getErrors() as $error) {
                $this->addError($error);
            }

            logs('amazon_pay_v2')->info('[注文処理] PaymentMethod::checkoutのエラーのため, 購入エラー画面へ遷移します.', [$PaymentResult->getErrors()]);

            return $this->redirectToRoute('shopping_error');
        }
    }

    private function getAmazonCustomerParam($request)
    {
        $customer_regist_v2 = empty($request->get('_shopping_order')['customer_regist_v2']) ? false : true;
        $mail_magazine = empty($request->get('_shopping_order')['mail_magazine']) ? false : true;
        $login_check_v2 = empty($request->get('_shopping_order')['login_check_v2']) ? null : $request->get('_shopping_order')['login_check_v2'];
        $amazon_login_email_v2 = empty($request->get('_shopping_order')['amazon_login_email_v2']) ? null : $request->get('_shopping_order')['amazon_login_email_v2'];
        $amazon_login_password_v2 = empty($request->get('_shopping_order')['amazon_login_password_v2']) ? null : $request->get('_shopping_order')['amazon_login_password_v2'];

        return [
            'customer_regist_v2' => $customer_regist_v2,
            'mail_magazine' => $mail_magazine,
            'login_check_v2' => $login_check_v2,
            'amazon_login_email_v2' => $amazon_login_email_v2,
            'amazon_login_password_v2' => $amazon_login_password_v2,
        ];
    }

    private function checkAmazonCustomerError($request, $form, $Order)
    {
        $arrError = [];

        // 未ログイン時、会員登録フォームを表示
        if (
            !$this->isGranted('IS_AUTHENTICATED_FULLY') &&
            $this->Config->getLoginRequired() == $this->eccubeConfig['amazon_pay_v2']['toggle']['on']
        ) {
            $request_uri = $request->getUri();
            if (
                'POST' === $request->getMethod()
                && strpos($request_uri, 'shopping/amazon_pay/address') === false
                && strpos($request_uri, 'shopping/amazon_pay/delivery') === false
            ) {
                // 会員登録フォームのエラーチェック
                $login_check_v2 = $form['login_check_v2']->getData();
                if ($login_check_v2 == 'regist') {
                    if (empty($form['customer_regist_v2']->getData())) {
                        $arrError['customer_regist_v2'] = '※ 会員登録が選択されていません。';
                    } else {
                        $Customer = $this->customerRepository->getNonWithdrawingCustomers(['email' => $Order->getEmail()]);

                        // AmazonアカウントのIDで会員情報取得
                        $profile = unserialize($this->session->get($this->sessionAmazonProfileKey));
                        $AmazonCustomer = $this->customerRepository->getNonWithdrawingCustomers(['v2_amazon_user_id' => $profile->buyerId]);
                        if (!empty($Customer[0])) {
                            $arrError['customer_regist_v2'] = '※ 会員登録済みです。メールアドレスは' . $Order->getEmail() . 'です。';
                        } else if (!empty($AmazonCustomer[0])) {
                            $arrError['customer_regist_v2'] = '※ このAmazonアカウントで既に会員登録済みです。メールアドレスは' . $AmazonCustomer[0]->getEmail() . 'です。';
                        }
                    }
                } else if ($login_check_v2 == 'login') {

                    $violations = $this->validator->validate($form['amazon_login_email_v2']->getData(), [
                        new Assert\NotBlank(),
                        new Assert\Email(),
                    ]);

                    $amazon_login_email_v2_error = '';

                    foreach ($violations as $violation) {
                        $amazon_login_email_v2_error .= $violation->getMessage() . PHP_EOL;
                    }

                    if (!empty($amazon_login_email_v2_error)) {
                        $arrError['amazon_login_email_v2'] = '※ メールアドレスが' . $amazon_login_email_v2_error;
                    }

                    $violations = $this->validator->validate($form['amazon_login_password_v2']->getData(), [
                        new Assert\NotBlank(),
                    ]);

                    $amazon_login_password_v2_error = '';

                    foreach ($violations as $violation) {
                        $amazon_login_password_v2_error .= $violation->getMessage() . PHP_EOL;
                    }

                    if (!empty($amazon_login_password_v2_error)) {
                        $arrError['amazon_login_password_v2'] = '※ パスワードが' . $amazon_login_password_v2_error;
                        // $form['amazon_login_password_v2']->addError(new FormError('※ パスワードが' . $amazon_login_password_v2_error));
                    }

                    if (empty($login_check_v2_error) && empty($amazon_login_email_v2_error) && empty($amazon_login_password_v2_error)) {
                        $Customer = $this->customerRepository->getNonWithdrawingCustomers(['email' => $form['amazon_login_email_v2']->getData()]);
                        if (empty($Customer[0])) {
                            $arrError['amazon_login_email_v2'] = '※ メールアドレスまたはパスワードが正しくありません。';
                            // $form['amazon_login_email_v2']->addError(new FormError('※ メールアドレスまたはパスワードが正しくありません。'));
                        } else {
                            $encoder = $this->encoderFactory;

                            if (!$encoder->isPasswordValid($Customer[0], $form['amazon_login_password_v2']->getData())) {
                                    $arrError['amazon_login_email_v2'] = '※ メールアドレスまたはパスワードが正しくありません。';
                                // $form['amazon_login_email_v2']->addError(new FormError('※ メールアドレスまたはパスワードが正しくありません。'));
                            }
                        }
                    }
                }
            }
        }

        return $arrError;
    }

    private function setLogin($request, $Order, $email)
    {
        $ret = false;   // ログイン処理正常終了時true
        try{
            $Customer = $this->customerRepository->getNonWithdrawingCustomers(['email' => $email]);

            // 取得した会員を設定
            $Order->setCustomer($Customer[0]);
            // ログイン状態とする
            $token = new UsernamePasswordToken($Customer[0], 'customer', ['ROLE_USER']);
            $this->tokenStorage->setToken($token);

            // 注文者情報をログイン会員の情報に置き換える
            $this->amazonOrderHelper->copyToOrderFromCustomer($Order, $Customer[0]);
            $ret = true;
        } catch (\Exception $e) {
            // 会員情報が取得できなかった場合など
            logs('amazon_pay_v2')->error($e);
        }
        return $ret;
    }

    private function setAmazonCustomerData($form, $arrAmazonCustomerParam)
    {
        $form->get('customer_regist_v2')->setData($arrAmazonCustomerParam['customer_regist_v2']);
        if (
            $this->pluginRepository->findOneBy(['code' => 'MailMagazine42', 'enabled' => true])
            || $this->pluginRepository->findOneBy(['code' => 'PostCarrier42', 'enabled' => true])
        ) {
            $form->get('mail_magazine')->setData($arrAmazonCustomerParam['mail_magazine']);
        }
        if (
            $this->Config->getLoginRequired() == $this->eccubeConfig['amazon_pay_v2']['toggle']['on'] &&
            !$this->isGranted('IS_AUTHENTICATED_FULLY')
        ) {
            $form->get('login_check_v2')->setData($arrAmazonCustomerParam['login_check_v2']);
            $form->get('amazon_login_email_v2')->setData($arrAmazonCustomerParam['amazon_login_email_v2']);
            $form->get('amazon_login_password_v2')->setData($arrAmazonCustomerParam['amazon_login_password_v2']);
        }
    }

    /**
     * 決済処理中の受注を取得する.
     *
     * @param null|string $preOrderId
     *
     * @return null|Order
     */
    public function getPendingProcessingOrder($preOrderId = null)
    {
        if (null === $preOrderId) {
            return null;
        }

        $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PENDING);

        return $this->orderRepository->findOneBy([
                                                     'pre_order_id' => $preOrderId,
                                                     'OrderStatus' => $OrderStatus,
                                                 ]);
    }

    private function checkShippingDifference($Order, $shippingAddress)
    {
        $amazonShipping = new Shipping();
        $amazonShipping->setOrder($Order);
        $this->amazonOrderHelper->convert($amazonShipping, $shippingAddress);
        $Shippings = $Order->getShippings();
        $shippingDifference = false;

        if (
            $Shippings->first()->getPostalCode() !== $amazonShipping->getPostalCode()
            || $Shippings->first()->getName01() !== $amazonShipping->getName01()
            || $Shippings->first()->getName02() !== $amazonShipping->getName02()
            || $Shippings->first()->getKana01() !== $amazonShipping->getKana01()
            || $Shippings->first()->getKana02() !== $amazonShipping->getKana02()
            || $Shippings->first()->getPref() !== $amazonShipping->getPref()
            || $Shippings->first()->getAddr01() !== $amazonShipping->getAddr01()
            || $Shippings->first()->getAddr02() !== $amazonShipping->getAddr02()
        ) {
            $shippingDifference = true;
        }

        return $shippingDifference;
    }

    /**
     * @param Shipping $Shipping
     *
     * @return Delivery $Delivery
     */
    protected function getAmazonPayDefaultDelivery(Shipping $Shipping)
    {
        // 配送商品に含まれる販売種別を抽出.
        $OrderItems = $Shipping->getProductOrderItems();
        $SaleTypes = [];
        /** @var OrderItem $OrderItem */
        foreach ($OrderItems as $OrderItem) {
            $ProductClass = $OrderItem->getProductClass();
            $SaleType = $ProductClass->getSaleType();
            $SaleTypes[$SaleType->getId()] = $SaleType;
        }

        // 販売種別に紐づく配送業者を取得.
        $Deliveries = $this->deliveryRepository->getDeliveries($SaleTypes);

        // AmazonPayが含まれない配送業者をUnset
        foreach ($Deliveries as $key => $Delivery) {
            $PaymentOptions = $Delivery->getPaymentOptions();
            $amazonPayFlg = false;
            foreach ($PaymentOptions as $PaymentOption) {
                $Payment = $PaymentOption->getPayment();
                if ($Payment->getMethodClass() === AmazonPay::class) {
                    $amazonPayFlg = true;
                    break;
                }
            }

            if (!$amazonPayFlg) {
                unset($Deliveries[$key]);
            }
        }

        //先頭の配送業者を取得
        $Delivery = current($Deliveries);

        return $Delivery;;
    }
}
