<?php

namespace Plugin\EccubePaymentLite42\EventListener\EventSubscriber\Admin\Order;

use Eccube\Entity\Order;
use Eccube\Event\TemplateEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddPaymentStatusFormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $parameter_bag)
    {
        $this->container = $parameter_bag;
    }

    public static function getSubscribedEvents()
    {
        return [
            '@admin/Order/edit.twig' => 'edit',
        ];
    }

    public function edit(TemplateEvent $event)
    {
        /** @var Order $Order */
        $Order = $event->getParameter('Order');
        // 新規作成時は決済ステータスを表示しない
        if (is_null($Order->getId())) {
            return;
        }
        // TODO GMOイプシロンの決済ではない場合は表示しない
        // payment_methodをチェック
        $event->addSnippet('@EccubePaymentLite42/admin/Order/payment_status_form.twig');
    }
}
