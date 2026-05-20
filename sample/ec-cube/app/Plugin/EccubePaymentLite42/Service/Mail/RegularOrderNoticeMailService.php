<?php

namespace Plugin\EccubePaymentLite42\Service\Mail;

use Eccube\Entity\MailTemplate;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\MailTemplateRepository;
use Eccube\Service\MailService;
use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Plugin\EccubePaymentLite42\Repository\RegularShippingRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class RegularOrderNoticeMailService
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
     * @var MailService
     */
    private $mailService;
    /**
     * @var MailTemplateRepository
     */
    private $mailTemplateRepository;
    /**
     * @var RegularShippingRepository
     */
    private $regularShippingRepository;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        BaseInfoRepository $baseInfoRepository,
        MailTemplateRepository $mailTemplateRepository,
        MailService $mailService,
        RegularShippingRepository $regularShippingRepository,
        ConfigRepository $configRepository
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->baseInfoRepository = $baseInfoRepository;
        $this->mailTemplateRepository = $mailTemplateRepository;
        $this->mailService = $mailService;
        $this->regularShippingRepository = $regularShippingRepository;
        $this->configRepository = $configRepository;
    }

    public function sendMail(RegularOrder $RegularOrder)
    {
        $Customer = $RegularOrder->getCustomer();
        $BaseInfo = $this->baseInfoRepository->find(1);
        /** @var MailTemplate $MailTemplate */
        $MailTemplate = $this->mailTemplateRepository->findOneBy([
            'file_name' => 'EccubePaymentLite42/Resource/template/default/Mail/regular_notice_mail.twig',
        ]);
        /** @var Config $Config */
        $Config = $this->configRepository->find(1);
        // 定期配送事前お知らせメール配信日が空の場合はメールを送信しない。
        $regularDeliveryNotificationEmailDate = $Config->getRegularDeliveryNotificationEmailDays();
        if (is_null($regularDeliveryNotificationEmailDate)) {
            return;
        }
        $body = $this->twig->render($MailTemplate->getFileName(), [
            'BaseInfo' => $BaseInfo,
            'RegularOrder' => $RegularOrder,
        ]);

        $message = (new Email())
            ->subject('['.$BaseInfo->getShopName().'] '.$MailTemplate->getMailSubject())
            ->from(new Address($BaseInfo->getEmail01(), $BaseInfo->getShopName()))
            ->to($Customer->getEmail())
            ->replyTo($BaseInfo->getEmail03())
            ->returnPath($BaseInfo->getEmail04());
        $htmlFileName = $this->mailService->getHtmlTemplate($MailTemplate->getFileName());
        if (!is_null($htmlFileName)) {
            $htmlBody = $this->twig->render($htmlFileName, [
                'BaseInfo' => $BaseInfo,
                'RegularOrder' => $RegularOrder,
            ]);

            $message
                ->text($body)
                ->html($htmlBody);
//                ->setContentType('text/plain; charset=UTF-8')
//                ->setBody($body, 'text/plain')
//                ->addPart($htmlBody, 'text/html');
        } else {
            $message->text($body);
        }

        return $this->mailer->send($message);
    }
}
