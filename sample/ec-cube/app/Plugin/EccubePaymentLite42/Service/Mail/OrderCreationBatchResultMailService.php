<?php

namespace Plugin\EccubePaymentLite42\Service\Mail;

use Eccube\Repository\BaseInfoRepository;
use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class OrderCreationBatchResultMailService
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var BaseInfoRepository
     */
    private $baseInfoRepository;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        BaseInfoRepository $baseInfoRepository,
        ConfigRepository $configRepository
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->baseInfoRepository = $baseInfoRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @param $PaymentErrorRegularOrders
     *
     * @return int|void
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMail(
        $PaymentErrorRegularOrders,
        int $numberOfRegularOrders,
        int $numberOfCompletedOrder,
        int $numberOfPaymentError,
        int $numberOfSystemError)
    {
        $body = $this->twig->render('EccubePaymentLite42/Resource/template/default/Mail/order_creation_batch_result_mail.twig', [
            'now' => new \DateTime(),
            'PaymentErrorRegularOrders' => $PaymentErrorRegularOrders,
            'numberOfRegularOrders' => $numberOfRegularOrders,
            'numberOfCompletedOrder' => $numberOfCompletedOrder,
            'numberOfPaymentError' => $numberOfPaymentError,
            'numberOfSystemError' => $numberOfSystemError,
        ]);

        $BaseInfo = $this->baseInfoRepository->find(1);
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);
        $message = (new Email())
            ->subject('['.$BaseInfo->getShopName().'] '.'受注作成バッチ実行結果通知メール')
            ->from(new Address($BaseInfo->getEmail01(), $BaseInfo->getShopName()))
            ->to($Config->getRegularOrderNotificationEmail())
            ->replyTo($BaseInfo->getEmail03())
            ->returnPath($BaseInfo->getEmail04());
        $message->text($body);

        return $this->mailer->send($message);
    }
}
