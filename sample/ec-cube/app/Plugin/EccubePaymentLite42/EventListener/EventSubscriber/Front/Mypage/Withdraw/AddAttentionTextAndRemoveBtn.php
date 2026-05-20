<?php

namespace Plugin\EccubePaymentLite42\EventListener\EventSubscriber\Front\Mypage\Withdraw;

use Eccube\Event\TemplateEvent;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Entity\RegularStatus;
use Plugin\EccubePaymentLite42\Repository\RegularOrderRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AddAttentionTextAndRemoveBtn implements EventSubscriberInterface
{
    /**
     * @var RegularOrderRepository
     */
    private $regularOrderRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        RegularOrderRepository $regularOrderRepository,
        RequestStack $requestStack
    ) {
        $this->regularOrderRepository = $regularOrderRepository;
        $this->session = $requestStack->getSession();
    }

    public static function getSubscribedEvents()
    {
        return [
            'Mypage/withdraw.twig' => 'index',
        ];
    }

    public function index(TemplateEvent $templateEvent)
    {
        $Customer = unserialize($this->session->get('_security_customer'))->getUser();
        /** @var RegularOrder[] $RegularOrders */
        $RegularOrders = $this->regularOrderRepository->findBy([
            'Customer' => $Customer,
        ]);
        foreach ($RegularOrders as $RegularOrder) {
            if ($RegularOrder->getRegularStatus()->getId() === RegularStatus::CANCELLATION) {
                continue;
            }
            $templateEvent->addSnippet('@EccubePaymentLite42/default/Mypage/Withdraw/attention_text_and_remove_btn.twig');

            return;
        }
    }
}
