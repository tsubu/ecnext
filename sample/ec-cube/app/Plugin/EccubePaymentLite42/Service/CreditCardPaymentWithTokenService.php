<?php

namespace Plugin\EccubePaymentLite42\Service;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Order;
use Plugin\EccubePaymentLite42\Service\GmoEpsilonRequest\RequestDirectCardPaymentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreditCardPaymentWithTokenService
{
    /**
     * @var RequestDirectCardPaymentService
     */
    private $requestDirectCardPaymentService;
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var GmoEpsilonRequestService
     */
    private $gmoEpsilonRequestService;

    public function __construct(
        RequestDirectCardPaymentService $requestDirectCardPaymentService,
        EccubeConfig $eccubeConfig,
        Environment $twig,
        GmoEpsilonRequestService $gmoEpsilonRequestService,
        RouterInterface $router
    ) {
        $this->requestDirectCardPaymentService = $requestDirectCardPaymentService;
        $this->eccubeConfig = $eccubeConfig;
        $this->twig = $twig;
        $this->gmoEpsilonRequestService = $gmoEpsilonRequestService;
        $this->router = $router;
    }

    public function handle(string $token, string $stCode, $dispatcher, Order $Order)
    {
        $results = $this
            ->requestDirectCardPaymentService
            ->handle(
                $Order,
                1,
                $stCode,
                'shopping_checkout',
                $token
            );
        if ($results['status'] === 'NG') {
            $message = $results['message'];
            $content = $this->twig->render('error.twig', [
                'error_title' => trans('gmo_epsilon.front.shopping.error'),
                'error_message' => $message,
            ]);
            $dispatcher->setResponse(new Response($content));

            return $dispatcher;
        }

        $Order->setTransCode($results['trans_code']);
        $Order->setGmoEpsilonOrderNo($results['order_number']);

        // 3DS処理（カード会社に接続必要）
        if ($results['result'] === $this->eccubeConfig['gmo_epsilon']['receive_parameters']['result']['3ds']) {
            // 3Dセキュア認証送信パラメータ1　加盟店様⇒カード会社
            $content = $this->twig->render('@EccubePaymentLite42/default/Shopping/transition_3ds_screen.twig', [
                'AcsUrl' => $results['acsurl'],
                'PaReq' => $results['pareq'],
                'TermUrl' => $this->router->generate('eccube_payment_lite42_reception_3ds', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'MD' => $Order->getPreOrderId(),
            ]);
            $dispatcher->setResponse(new Response($content));

            return $dispatcher;
        }

        // 3DS 2.0 処理（カード会社に接続必要）
        if ($results['result'] === $this->eccubeConfig['gmo_epsilon']['receive_parameters']['result']['3ds2']) {
            $url = $this->router->generate('eccube_payment_lite42_reception_3ds2', [], UrlGeneratorInterface::ABSOLUTE_URL);
            // 3D 2.0 セキュア認証送信パラメータ1　加盟店様⇒カード会社
            $content = $this->twig->render('@EccubePaymentLite42/default/Shopping/transition_3ds2_screen.twig', [
                'ACSUrl' => $results['tds2_url'],
                'TermUrl' => $url,
                'MD' => $Order->getPreOrderId(),
                'PaReq' => $results['pareq'],
            ]);
            // start write log for sent parameters to カード会社に接続必要
            logs('gmo_epsilon')->info('Parameter sent 3DS 2.0 処理（カード会社に接続必要） (ACSUrl):  = '.$results['tds2_url']);
            logs('gmo_epsilon')->info('Parameter sent 3DS 2.0 処理（カード会社に接続必要） (TermUrl):  = '.$url);
            logs('gmo_epsilon')->info('Parameter sent 3DS 2.0 処理（カード会社に接続必要） (MD):  = '.$Order->getPreOrderId());
            logs('gmo_epsilon')->info('Parameter sent 3DS 2.0 処理（カード会社に接続必要） (PaReq):  = '.$results['pareq']);
            // end write log for sent parameters to カード会社に接続必要
            $dispatcher->setResponse(new Response($content));

            return $dispatcher;
        }

        return false;
    }
}
