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

namespace Plugin\AmazonPayV2_42_Bundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Service\CartService;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Service\PurchaseFlow\Processor\StockReduceProcessor;
use Eccube\Service\PurchaseFlow\Processor\PointProcessor;
use Plugin\AmazonPayV2_42_Bundle\Entity\Master\AmazonStatus;
use Plugin\AmazonPayV2_42_Bundle\Exception\AmazonException;
use Plugin\AmazonPayV2_42_Bundle\Exception\AmazonPaymentException;
use Plugin\AmazonPayV2_42_Bundle\Repository\ConfigRepository;
use Plugin\AmazonPayV2_42_Bundle\Amazon\Pay\API\Client as AmazonPayClient;
use Plugin\AmazonPayV2_42_Bundle\Repository\Master\AmazonStatusRepository;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Carbon\Carbon;

class AmazonRequestService extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var BaseInfoRepository
     */
    protected $baseInfoRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var PurchaseFlow
     */
    protected $purchaseFlow;

    /**
     * @var eccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * @var Config
     */
    protected $Config;

    /**
     * @var array
     */
    protected $amazonApi;

    /**
     * @var array
     */
    protected $amazonApiConfig;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var PointProcessor
     */
    private $pointProcessor;

    /**
     * @var StockReduceProcessor
     */
    private $stockReduceProcessor;

    /**
     * @var AmazonStatusRepository
     */
    private $amazonStatusRepository;

    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        BaseInfoRepository $baseInfoRepository,
        CustomerRepository $customerRepository,
        CartService $cartService,
        PurchaseFlow $cartPurchaseFlow,
        EccubeConfig $eccubeConfig,
        ConfigRepository $configRepository,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        OrderStatusRepository $orderStatusRepository,
        AmazonStatusRepository $amazonStatusRepository,
        StockReduceProcessor $stockReduceProcessor,
        PointProcessor $pointProcessor,
        ContainerInterface $container
    ) {
        $this->entityManager = $entityManager;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->customerRepository = $customerRepository;
        $this->cartService = $cartService;
        $this->purchaseFlow = $cartPurchaseFlow;
        $this->eccubeConfig = $eccubeConfig;
        $this->configRepository = $configRepository;
        $this->session = $requestStack->getSession();
        $this->tokenStorage = $tokenStorage;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->amazonStatusRepository = $amazonStatusRepository;
        $this->stockReduceProcessor = $stockReduceProcessor;
        $this->pointProcessor = $pointProcessor;
        $this->container = $container;

        $this->Config = $this->configRepository->get();
        if (
            $this->Config->getAmazonAccountMode() == $this->eccubeConfig['amazon_pay_v2']['account_mode']['owned'] &&
            $this->Config->getEnv() == $this->eccubeConfig['amazon_pay_v2']['env']['prod']
        ) {
            $this->amazonApi = $this->eccubeConfig['amazon_pay_v2']['api']['prod'];
        } else {
            $this->amazonApi = $this->eccubeConfig['amazon_pay_v2']['api']['sandbox'];
        }
        $this->amazonApiConfig = $this->eccubeConfig['amazon_pay_v2']['api']['config'];
    }

    private function payoutSellerOrderId($orderId, $request_type = '')
    {
        $request_attr = $request_type === '' ? '' : strtoupper($request_type) . '_';

        $prefix = '';
        $iniFile = dirname(__FILE__) . '/../amazon_pay_config.ini';
        if (file_exists($iniFile)) {
            $arrInit = parse_ini_file($iniFile);
            $prefix = $arrInit['prefix'];
        }
        $prefix = $prefix === '' ? '' : $prefix . '_';

        $timestamp = '';
        if ($this->Config->getAmazonAccountMode() === $this->eccubeConfig['amazon_pay_v2']['account_mode']['shared']) {
            $timestamp = Carbon::now()->timestamp;
        }
        $timestamp = $timestamp === '' ? '' : $timestamp . '_';

        return $timestamp . $prefix . $request_attr . $orderId;
    }

    protected function getAmazonPayConfig()
    {
        $Config = $this->configRepository->get();

        $config = [
            'public_key_id' => $Config->getPublicKeyId(),
            'private_key'   => $this->eccubeConfig->get('kernel.project_dir') . '/' . $Config->getPrivateKeyPath(),
            'sandbox'       => $Config->getEnv() == $this->eccubeConfig['amazon_pay_v2']['env']['sandbox'] ? true : false,                        // true (Sandbox) or false (Production) boolean
            'region'        => 'jp'                         // Must be one of: 'us', 'eu', 'jp'
        ];

        return $config;
    }

    public function createCheckoutSessionPayload($cart_key)
    {
        $Config = $this->configRepository->get();
        $router = $this->container->get('router');

        $payload = [
            'webCheckoutDetails' => [
                'checkoutReviewReturnUrl' => $router->generate('amazon_checkout_review', ['cart' => $cart_key], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
            'paymentDetails' => [
                'allowOvercharge' => true, //増額許可
            ],
            'storeId' => $Config->getClientId(),
            'deliverySpecifications' => [
                'addressRestrictions' => [
                    'type' => 'Allowed',
                    'restrictions' => [
                        'JP' => [],
                    ],
                ],
            ],
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function createUpdateCheckoutSessionPayload($Order)
    {
        $router = $this->container->get('router');
        
        // NOTE: AmazonPayAPI仕様上、0円の決済は許容しない.
        if($Order->getPaymentTotal() == 0) {
            throw AmazonPaymentException::create(AmazonPaymentException::ZERO_PAYMENT);
        }

        $config = $this->configRepository->get();
        if ($config->getSale() == $this->eccubeConfig['amazon_pay_v2']['sale']['authori']) {
            $paymentIntent = 'Authorize';
        } elseif ($config->getSale() == $this->eccubeConfig['amazon_pay_v2']['sale']['capture']) {
            $paymentIntent = 'AuthorizeWithCapture';
        }

        $payload = [
            'webCheckoutDetails' => [
                'checkoutResultReturnUrl' => $router->generate('amazon_pay_shopping_checkout_result', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
            'paymentDetails' => [
                'paymentIntent' => $paymentIntent,
                'canHandlePendingAuthorization' => false,
                'chargeAmount' => [
                    'amount' => (int)$Order->getPaymentTotal(),
                    'currencyCode' => "JPY"
                ],
                //"softDescriptor" => "softDescriptor"
            ],
            'merchantMetadata' => [
                'merchantReferenceId' => $this->payoutSellerOrderId($Order->getId()),
                'noteToBuyer' => ''
                // "customInformation" => "customInformation"
            ],
            "platformId" => "A34OGIQFKZAIA6"
        ];

        // 店舗名が50文字を超えるとAmazon側でエラーとなるため考慮する
        if (mb_strlen($this->BaseInfo->getShopName()) < 51) {
            $payload['merchantMetadata']['merchantStoreName'] = $this->BaseInfo->getShopName();
        }

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function createCompleteCheckoutSessionPayload($Order)
    {

        $payload = [
            'chargeAmount' => [
                'amount' => (int)$Order->getPaymentTotal(),
                'currencyCode' => 'JPY',
            ]
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function createCaptureChargePayload($Order, $billingAmount = null)
    {
        $payload = [
            'captureAmount' => [
                'amount' => is_null($billingAmount) ? (int)$Order->getPaymentTotal() : $billingAmount,
                'currencyCode' => 'JPY',
            ]
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function createCancelChargePayload($cancellationReason = null)
    {
        $payload = [
            'cancellationReason' => $cancellationReason
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function createCloseChargePermissionPayload($closureReason = null, $cancelPendingCharges = null)
    {
        $payload = [
            'closureReason' => $closureReason,
            'cancelPendingCharges' => $cancelPendingCharges
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function createCreateRefundPayload($chargeId, $refundAmount)
    {
        $payload = [
            'chargeId' => $chargeId,
            'refundAmount' => [
                'amount' => $refundAmount,
                'currencyCode' => $this->eccubeConfig['amazon_pay_v2']['api']['payload']['currency_code'],
            ]
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function createCreateChargePayload($chargePermissionId, $paymentTotal, $CaptureNow = false, $canHandlePendingAuthorization = false)
    {
        $payload = [
            'chargePermissionId' => $chargePermissionId,
            'chargeAmount' => [
                'amount' => $paymentTotal,
                'currencyCode' => $this->eccubeConfig['amazon_pay_v2']['api']['payload']['currency_code']
            ],
            'captureNow' => $CaptureNow,
            'canHandlePendingAuthorization' => $canHandlePendingAuthorization
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    public function updateCheckoutSession($Order, $amazonCheckoutSessionId)
    {
        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->updateCheckoutSession($amazonCheckoutSessionId, $this->createUpdateCheckoutSessionPayload($Order));


        return json_decode($result['response']);
    }

    public function signaturePayload($payload)
    {
        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $signature = $client->generateButtonSignature($payload);
        return $signature;
    }

    public function getCheckoutSession($amazonCheckoutSessionId)
    {
        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->getCheckoutSession($amazonCheckoutSessionId);
        return json_decode($result['response']);
    }

    public function completeCheckoutSession($Order, $amazonCheckoutSessionId)
    {
        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->completeCheckoutSession($amazonCheckoutSessionId, $this->createCompleteCheckoutSessionPayload($Order)); 

        $response = json_decode($result['response']);
        logs('amazon_pay_v2')->info('▼completeCheckoutSession http-status = ' . $result['status'] . ', order_id = ' . $Order->getId());

        if ($result['status'] == 200 || $result['status'] == 202) {
                if ($response->statusDetails->state == 'Completed') {
                    return $response;
                }
        } elseif (isset($response->reasonCode)) {
            logs('amazon_pay_v2')->info('▼completeCheckoutSession reasonCode = ' . $response->reasonCode . ', order_id = ' . $Order->getId());
            if ($response->reasonCode == 'CheckoutSessionCanceled') {
                // チェックアウトセッションを再取得し理由コードを取得する
                $checkoutSession = $this->getCheckoutSession($amazonCheckoutSessionId);

                if ($checkoutSession && isset($checkoutSession->statusDetails->reasonCode)) {
                    $errorCode = AmazonPaymentException::getErrorCode($checkoutSession->statusDetails->reasonCode);
                    logs('amazon_pay_v2')->info('▼completeCheckoutSession statusDetails = ' . var_export($checkoutSession->statusDetails, true));

                    // 購入者が決済をキャンセルしたなら受注もキャンセルにする
                    $this->cancelOrder($Order);
                    logs('amazon_pay_v2')->info('▼completeCheckoutSession 受注をキャンセルしました' . 'order_id = ' . $Order->getId());
                    
                    if ($errorCode) {
                        throw AmazonPaymentException::create($errorCode);
                    }
                }
            }
        }

        // 条件に合致しない場合は全てAmazonException()
        throw new AmazonException();

    }

    /**
     * 受注をキャンセルする
     * 
     * @param $Order キャンセル対象の受注
     */
    private function cancelOrder($Order)
    {   
        // 在庫・使用ポイント戻しはexecutePurchaseFlowで実施されるため
        // 自前実装不要

        $OrderStatus = $this->orderStatusRepository->find($this->orderStatusRepository->find(OrderStatus::CANCEL));
        $Order->setOrderStatus($OrderStatus);

        $AmazonStatus = $this->amazonStatusRepository->find(AmazonStatus::CANCEL);
        $Order->setAmazonPayV2AmazonStatus($AmazonStatus);

        $this->entityManager->flush();
    }

    public function captureCharge($chargeId, $Order, $billingAmount = null)
    {
        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $headers = ['x-amz-pay-Idempotency-Key' => uniqid()];

        $result = $client->captureCharge($chargeId, $this->createCaptureChargePayload($Order, $billingAmount), $headers);

        return json_decode($result['response']);
    }

    /**
     * 売上をキャンセル
     *
     * @param string $chargeId Amazon注文参照ID
     * @return array or string リクエスト結果
     */
    public function cancelCharge($chargeId, $cancellationReason = null)
    {
        $payload = $this->createCancelChargePayload($cancellationReason);

        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->cancelCharge($chargeId, $payload);

        return json_decode($result['response']);

    }

    /**
     * 注文取消
     */
    public function closeChargePermission($chargePermissionId, $closureReason = null, $cancelPendingCharges = true)
    {
        $payload = $this->createCloseChargePermissionPayload($closureReason, $cancelPendingCharges);

        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->closeChargePermission($chargePermissionId, $payload);

        return json_decode($result['response']);
    }

    /**
     * 請求済み売り上げを返金
     *
     * @param string $amazonCaptureId Amazon取引ID
     * @param string $chargeId 注文ID
     * @param integer $refundAmoun 返金金額
     * @return array or string リクエスト結果
     */
    public function createRefund($chargeId, $refundAmount, $softDescriptor = null, $idempotencyKey = null)
    {
        $payload = $this->createCreateRefundPayload($chargeId, $refundAmount);

        if (null != $softDescriptor) {
            $payload = array_merge($payload, ["softDescriptor" => $softDescriptor]);
        }

        if ($idempotencyKey == null) {
            $idempotencyKey = uniqid();
        }

        $headers = ['x-amz-pay-Idempotency-Key' => $idempotencyKey];

        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->createRefund($payload, $headers);

        return json_decode($result['response']);
    }

    /**
     * 購入を確定(オーソリのリクエスト)
     *
     * @param string $amazonOrderReferenceId Amazon注文参照ID
     * @param integer $order_id 注文ID
     * @param integer $payment_total 受注金額合計
     * @return array or string リクエスト結果
     */
    public function createCharge($chargePermissionId, $paymentTotal, $CaptureNow = false, $softDescriptor = null, $canHandlePendingAuthorization = false, $merchantMetadataMerchantReferenceId = null, $idempotencyKey = null)
    {
        $payload = $this->createCreateChargePayload($chargePermissionId, $paymentTotal, $CaptureNow, $canHandlePendingAuthorization);

        if (null != $merchantMetadataMerchantReferenceId) {
            $payload = array_merge($payload, [
                "merchantMetadata" => [
                    "merchantReferenceId"=> $merchantMetadataMerchantReferenceId
                ]
            ]);
        }

        if (null != $softDescriptor) {
            $payload = array_merge($payload, ["softDescriptor" => $softDescriptor]);
        }

        if ($idempotencyKey == null) {
            $idempotencyKey = uniqid();
        }

        $headers = ['x-amz-pay-Idempotency-Key' => $idempotencyKey];

        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->createCharge($payload, $headers);

        return json_decode($result['response']);
    }

    /**
     * 請求情報取得
     * @param string $chargeId Amazon請求情報参照ID
     * @return array or string リクエスト結果
     */
    public function getCharge($chargeId)
    {
        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->getCharge($chargeId);

        return json_decode($result['response']);
    }

    public function createSigninPayload($returnUrl)
    {
        $Config = $this->configRepository->get();

        $payload = [
            'signInReturnUrl' => $returnUrl,
            'storeId' => $Config->getClientId(),
        ];

        return json_encode($payload, JSON_FORCE_OBJECT);
    }

    /**
     * 購入者情報取得
     * @param string $buyerToken 購入者情報トークン
     * @param array $headers ヘッダー
     * @return array or string リクエスト結果
     */
    public function getBuyer($buyerToken, $headers = null)
    {
        $client = new AmazonPayClient($this->getAmazonPayConfig());

        $result = $client->getBuyer($buyerToken, $headers);
        if ($result['status'] != 200) {
            throw new AmazonException();
        }
        return json_decode($result['response']);
    }

    /**
     * 取得したbuyerIdに一致した会員でログインを行う
     *
     * @param Request $request
     * @param string $buyerId
     * @return bool
     */
    public function loginWithBuyerId(Request $request, $buyerId)
    {
        // buyerIdで会員を検索する
        $Customers = $this->customerRepository->getNonWithdrawingCustomers(['v2_amazon_user_id' => $buyerId]);
        if (empty($Customers[0]) || !$Customers[0] instanceof \Eccube\Entity\Customer) {
            return false;
        }

        // ログイン処理
        $token = new UsernamePasswordToken($Customers[0], 'customer', ['ROLE_USER']);
        $this->tokenStorage->setToken($token);
        $request->getSession()->migrate(true);

        // 未ログインカートとログイン済みカートのマージ処理
        $this->cartService->mergeFromPersistedCart();
        foreach ($this->cartService->getCarts() as $Cart) {
            $this->purchaseFlow->validate($Cart, new PurchaseContext($Cart, $Customers[0]));
        }
        $this->cartService->save();

        return true;
    }
}
