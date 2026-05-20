<?php

namespace Plugin\EccubePaymentLite42\Service\Mail;

use Eccube\Entity\MailTemplate;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\MailTemplateRepository;
use Eccube\Service\MailService;
use Plugin\EccubePaymentLite42\Entity\RegularOrder;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Plugin\EccubePaymentLite42\Repository\RegularShippingRepository;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class RegularSpecifiedCountNotificationMailService
{
    /**
     * @var Swift_Mailer
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
            'file_name' => 'EccubePaymentLite42/Resource/template/default/Mail/specified_count_notice_mail.twig',
        ]);

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
//
//                ->setContentType('text/plain; charset=UTF-8')
//                ->setBody($body, 'text/plain')
//                ->addPart($htmlBody, 'text/html');
        } else {
            $message->text($body);
        }

        return $this->mailer->send($message);
    }
}
