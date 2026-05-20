<?php

namespace Plugin\EccubePaymentLite42\EventListener\EventSubscriber\Front\Shopping;

use Eccube\Event\TemplateEvent;
use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Plugin\EccubePaymentLite42\Service\Method\Credit;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ChangeShoppingConfirmActionEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var ConfigRepository
     */
    private $configRepository;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ConfigRepository $configRepository,
        RouterInterface $router
    ) {
        $this->configRepository = $configRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopping/confirm.twig' => 'confirm',
        ];
    }

    public function confirm(TemplateEvent $event)
    {
        $PaymentMethod = $event->getParameter('Order')->getPayment()->getMethodClass();
        if (!($PaymentMethod === Credit::class)) {
            return;
        }
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);
        $creditPaymentSetting = $Config->getCreditPaymentSetting();
        if ($creditPaymentSetting === Config::LINK_PAYMENT) {
            return;
        }
        // トークン決済かつクレジットカード決済の場合に以下の処理を行う
        $url = $this->router->generate('eccube_payment_lite42_credit_card_for_token_payment');
        $event->setParameter('creditCardPaymentUrl', $url);
        $event->addSnippet('@EccubePaymentLite42/default/Shopping/change_shopping_confirm_action.twig');
    }
}
