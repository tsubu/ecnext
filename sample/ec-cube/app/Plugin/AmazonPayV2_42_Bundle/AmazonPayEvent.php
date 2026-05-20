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

namespace Plugin\AmazonPayV2_42_Bundle;

use Eccube\Event\TemplateEvent;
use Eccube\Event\EventArgs;
use Eccube\Event\EccubeEvents;
use Eccube\Common\EccubeConfig;
use Eccube\Repository\PaymentRepository;
use Eccube\Repository\PluginRepository;
use Eccube\Service\OrderHelper;
use Eccube\Service\CartService;
use Plugin\AmazonPayV2_42_Bundle\Repository\ConfigRepository;
use Plugin\AmazonPayV2_42_Bundle\Service\AmazonRequestService;
use Plugin\AmazonPayV2_42_Bundle\Service\Method\AmazonPay;
use Plugin\AmazonPayV2_42_Bundle\phpseclib\Crypt\Random;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Eccube\Repository\DeliveryRepository;
use Eccube\Repository\PaymentOptionRepository;
use Plugin\AmazonPayV2_42_Bundle\Entity\AmazonBanner;
use Plugin\AmazonPayV2_42_Bundle\Service\AmazonBannerService;

class AmazonPayEvent implements EventSubscriberInterface
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
     * @var string Amazonログインステート
     */
    private $sessionAmazonLoginStateKey = 'amazon_pay_v2.amazon_login_state';

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * @var AmazonRequestService
     */
    protected $amazonRequestService;

    /**
     * @var DeliveryRepository
     */
    protected $deliveryRepository;

    /**
     * @var DeliveryRepository
     */
    protected $paymentOptionRepository;

    /**
     * Amazon Payバナーサービス
     *
     * @var AmazonBannerService
     */
    protected $amazonBannerService;

    /**
     * AmazonPayEvent
     *
     * @param eccubeConfig $eccubeConfig
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        RequestStack $requestStack,
         
        TokenStorageInterface $tokenStorage,
        EccubeConfig $eccubeConfig,
        UrlGeneratorInterface $router,
        PaymentRepository $paymentRepository,
        PluginRepository $pluginRepository,
        ConfigRepository $configRepository,
        ContainerInterface $container,
        OrderHelper $orderHelper,
        CartService $cartService,
        AmazonRequestService $amazonRequestService,
        DeliveryRepository $deliveryRepository,
        PaymentOptionRepository $paymentOptionRepository,
        AmazonBannerService $amazonBannerService
    ) {
        $this->requestStack = $requestStack;
        $this->session = $requestStack->getSession();
        $this->tokenStorage = $tokenStorage;
        $this->eccubeConfig = $eccubeConfig;
        $this->router = $router;
        $this->paymentRepository = $paymentRepository;
        $this->pluginRepository = $pluginRepository;
        $this->configRepository = $configRepository;
        $this->container = $container;
        $this->orderHelper = $orderHelper;
        $this->cartService = $cartService;
        $this->amazonRequestService = $amazonRequestService;
        $this->deliveryRepository = $deliveryRepository;
        $this->paymentOptionRepository = $paymentOptionRepository;
        $this->amazonBannerService = $amazonBannerService;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::FRONT_CART_BUYSTEP_COMPLETE => 'amazon_cart_buystep',
            'Cart/index.twig' => 'cart',
            'Shopping/index.twig' => 'amazon_pay_shopping',
            'Mypage/login.twig' => 'mypage_login',
            'Shopping/confirm.twig' => 'amazon_pay_shopping_confirm',
            'index.twig' => 'add_banner_on_top',
        ];
    }

    /**
     * トップページのイベント
     * Amazon様バナーを挿入する
     *
     * @param TemplateEvent $event
     * @return void
     */
    public function add_banner_on_top(TemplateEvent $event)
    {
        $Config = $this->configRepository->get();
        
        if ($Config->getUseAmazonBannerOnTop() == $this->eccubeConfig['amazon_pay_v2']['toggle']['off']) {
            return;
        }

        if ($Config->getAmazonBannerPlaceOnTop() == $this->eccubeConfig['amazon_pay_v2']['button_place']['auto']) {
            $event->addSnippet('@AmazonPayV2_42_Bundle/default/amazon_banner_auto_on_top.twig');
        }
        
        $event->addSnippet($this->amazonBannerService->getBannerCodeOnTop(), false);
    }

    public function cart(TemplateEvent $event)
    {
        $Config = $this->configRepository->get();

        if ($Config->getUseAmazonBannerOnCart() != $this->eccubeConfig['amazon_pay_v2']['toggle']['off']) {

            if ($Config->getAmazonBannerPlaceOnCart() == $this->eccubeConfig['amazon_pay_v2']['button_place']['auto']) {
                $event->addSnippet('@AmazonPayV2_42_Bundle/default/amazon_banner_auto_on_cart.twig');
            }

            $event->addSnippet($this->amazonBannerService->getBannerCodeOnCart(), false);
        }

        $parameters = $event->getParameters();
        if (empty($parameters['Carts'])) {
            return;
        }

        if ($Config->getUseCartButton() == $this->eccubeConfig['amazon_pay_v2']['toggle']['off']) {
            return;
        }

        // AmazonPayに紐づく商品種別の取得
        $Payment = $this->paymentRepository->findOneBy(['method_class' => AmazonPay::class]);
        $AmazonDeliveries = $this->paymentOptionRepository->findBy(['payment_id' => $Payment->getId()]);
        $AmazonSaleTypes = [];
        foreach ($AmazonDeliveries as $AmazonDelivery) {
            $Delivery = $this->deliveryRepository->findOneBy(['id' => $AmazonDelivery->getDelivery()->getId()]);
            $AmazonSaleTypes[] = $Delivery->getSaleType()->getId();
        }
        $parameters['AmazonSaleTypes'] = $AmazonSaleTypes;

        foreach ($parameters['Carts'] as $Cart) {
            $cartKey = $Cart->getCartKey();
            $payload = $this->amazonRequestService->createCheckoutSessionPayload($Cart->getCartKey());
            $signature = $this->amazonRequestService->signaturePayload($payload);

            $parameters['cart'][$cartKey]['payload'] = $payload;
            $parameters['cart'][$cartKey]['signature'] = $signature;
        }

        $parameters['AmazonPayV2Config'] = $Config;
        if ($Config->getEnv() == $this->eccubeConfig['amazon_pay_v2']['env']['prod']) {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['prod'];
        } else {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['sandbox'];
        }
        $event->setParameters($parameters);

        $event->addSnippet('@AmazonPayV2_42_Bundle/default/Cart/amazon_pay_js.twig');

        if ($Config->getCartButtonPlace() == $this->eccubeConfig['amazon_pay_v2']['button_place']['auto']) {
            $event->addSnippet('@AmazonPayV2_42_Bundle/default/Cart/button.twig');
        }
    }

    public function amazon_cart_buystep(EventArgs $event)
    {
        // Amazonログインによる仮会員情報がセッションにセットされていたら
        if ($this->orderHelper->getNonmember() && $this->session->get($this->sessionAmazonProfileKey)) {
            // 仮会員情報を削除
            $this->session->remove(OrderHelper::SESSION_NON_MEMBER);
            $this->session->remove($this->sessionAmazonProfileKey);
            $this->cartService->setPreOrderId(null);
            $this->cartService->save();
        }
    }

    public function amazon_pay_shopping(TemplateEvent $event)
    {
        $request = $this->requestStack->getMainRequest();
        $uri = $request->getUri();

        $parameters = $event->getParameters();

        if (preg_match('/shopping\/amazon_pay/', $uri) == false) {
            $referer = $request->headers->get('referer');
            $Order = $parameters['Order'];
            $Payment = $Order->getPayment();
            // AmazonPay決済確認画面→クーポン入力画面→決済確認画面への遷移時にAmazonPay決済確認画面へ戻す
            if ($Payment && $Payment->getMethodClass() === AmazonPay::class && preg_match('/shopping_coupon/', $referer)) {
                header("Location:" . $this->router->generate('amazon_pay_shopping'));
                exit;
            }

            return;
        }

        $Config = $this->configRepository->get();

        $event->addSnippet('@AmazonPayV2_42_Bundle/default/Shopping/widgets.twig');
        $event->addSnippet('@AmazonPayV2_42_Bundle/default/Shopping/customer_regist_v2.twig');

        // チェックアウトセッションIDを取得する
        $amazonCheckoutSessionId = $this->session->get($this->sessionAmazonCheckoutSessionIdKey);

        $parameters = $event->getParameters();
        $parameters['amazonCheckoutSessionId'] = $amazonCheckoutSessionId;
        $parameters['AmazonPayV2Config'] = $Config;

        // メルマガプラグイン利用時はチェックボックスを追加
        if (
            $this->pluginRepository->findOneBy(['code' => 'MailMagazine42', 'enabled' => true])
            || $this->pluginRepository->findOneBy(['code' => 'PostCarrier42', 'enabled' => true])
        ) {
            $useMailMagazine = true;
        } else {
            $useMailMagazine = false;
        }
        $parameters['useMailMagazine'] = $useMailMagazine;

        if ($Config->getEnv() == $this->eccubeConfig['amazon_pay_v2']['env']['prod']) {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['prod'];
        } else {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['sandbox'];
        }
        $event->setParameters($parameters);
    }

    public function amazon_pay_shopping_confirm(TemplateEvent $event)
    {
        $request = $this->requestStack->getMainRequest();
        $uri = $request->getUri();

        if (preg_match('/shopping\/amazon_pay/', $uri) == false) {
            return;
        }
        $Config = $this->configRepository->get();

        $event->addSnippet('@AmazonPayV2_42_Bundle/default/Shopping/confirm_widgets.twig');
        $event->addSnippet('@AmazonPayV2_42_Bundle/default/Shopping/confirm_customer_regist_v2.twig');

        $parameters = $event->getParameters();
        $parameters['AmazonPayV2Config'] = $Config;

        // メルマガプラグイン利用時はチェックボックスを追加
        if (
            $this->pluginRepository->findOneBy(['code' => 'MailMagazine42', 'enabled' => true])
            || $this->pluginRepository->findOneBy(['code' => 'PostCarrier42', 'enabled' => true])
        ) {
            $useMailMagazine = true;
        } else {
            $useMailMagazine = false;
        }
        $parameters['useMailMagazine'] = $useMailMagazine;

        if ($Config->getEnv() == $this->eccubeConfig['amazon_pay_v2']['env']['prod']) {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['prod'];
        } else {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['sandbox'];
        }
        $event->setParameters($parameters);
    }

    public function mypage_login(TemplateEvent $event)
    {
        $Config = $this->configRepository->get();

        if ($Config->getUseMypageLoginButton() == $this->eccubeConfig['amazon_pay_v2']['toggle']['off']) {
            return;
        }

        $state = $this->session->get($this->sessionAmazonLoginStateKey);
        if (!$state) {
            // stateが生成されていなければ、生成及びセッションセット
            $state = bin2hex(Random::string(16));
            $this->session->set($this->sessionAmazonLoginStateKey, $state);
        }

        $returnUrl = $this->router->generate('login_with_amazon', ['state' => $state], UrlGeneratorInterface::ABSOLUTE_URL);

        $parameters = $event->getParameters();

        $payload = $this->amazonRequestService->createSigninPayload($returnUrl);
        $signature = $this->amazonRequestService->signaturePayload($payload);

        $parameters['payload'] = $payload;
        $parameters['signature'] = $signature;
        $parameters['buttonColor'] = $Config->getMypageLoginButtonColor();

        $parameters['AmazonPayV2Config'] = $Config;
        if ($Config->getEnv() == $this->eccubeConfig['amazon_pay_v2']['env']['prod']) {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['prod'];
        } else {
            $parameters['AmazonPayV2Api'] = $this->eccubeConfig['amazon_pay_v2']['api']['sandbox'];
        }

        $event->setParameters($parameters);

        if ($Config->getMypageLoginButtonPlace() == $this->eccubeConfig['amazon_pay_v2']['button_place']['auto']) {
            $event->addSnippet('@AmazonPayV2_42_Bundle/default/Mypage/login_page_button.twig');
        }

        $event->addSnippet('@AmazonPayV2_42_Bundle/default/Mypage/amazon_login_js.twig');
    }
}
