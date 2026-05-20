<?php

namespace Plugin\EccubePaymentLite42\Service;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Entity\MailTemplate;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\MailTemplateRepository;
use Eccube\Service\MailService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ChangeCardNotificationMailService
{
    /**
     * @var \MailerInterface
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
     * @var EccubeConfig
     */
    private $eccubeConfig;

    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        BaseInfoRepository $baseInfoRepository,
        MailTemplateRepository $mailTemplateRepository,
        MailService $mailService,
        EccubeConfig $eccubeConfig
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->baseInfoRepository = $baseInfoRepository;
        $this->mailTemplateRepository = $mailTemplateRepository;
        $this->mailService = $mailService;
        $this->eccubeConfig = $eccubeConfig;
    }

    public function sendMail(Customer $Customer)
    {
        $BaseInfo = $this->baseInfoRepository->find(1);
        $expireDateTime = $Customer
            ->getGmoEpsilonCreditCardExpirationDate();
        /** @var MailTemplate $MailTemplate */
        $MailTemplate = $this->mailTemplateRepository->findOneBy([
            'file_name' => 'EccubePaymentLite42/Resource/template/default/Mail/expiration_notice_mail.twig',
        ]);
        $body = $this->twig->render($MailTemplate->getFileName(), [
            'BaseInfo' => $BaseInfo,
            'Customer' => $Customer,
            'expireDateTime' => $expireDateTime,
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
                'Customer' => $Customer,
                'expireDateTime' => $expireDateTime,
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
