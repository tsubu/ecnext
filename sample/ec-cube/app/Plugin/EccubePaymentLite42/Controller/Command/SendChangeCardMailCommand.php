<?php

namespace Plugin\EccubePaymentLite42\Controller\Command;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Eccube\Repository\CustomerRepository;
use Plugin\EccubePaymentLite42\Entity\Config;
use Plugin\EccubePaymentLite42\Repository\ConfigRepository;
use Plugin\EccubePaymentLite42\Service\ChangeCardNotificationMailService;
use Plugin\EccubePaymentLite42\Service\GetCustomerForSendChangeCardMailService;
use Plugin\EccubePaymentLite42\Service\IsActiveRegularService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendChangeCardMailCommand extends Command
{
    protected static $defaultName = 'gmo_epsilon_4:regular:send_change_card_mail';
    /**
     * @var ChangeCardNotificationMailService
     */
    private $changeCardNotificationMailService;
    /**
     * @var SymfonyStyle
     */
    private $io;
    /**
     * @var IsActiveRegularService
     */
    private $isActiveRegularService;
    /**
     * @var GetCustomerForSendChangeCardMailService
     */
    private $getCustomerForSendChangeCardMailService;
    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(
        ChangeCardNotificationMailService $changeCardNotificationMailService,
        IsActiveRegularService $isActiveRegularService,
        GetCustomerForSendChangeCardMailService $getCustomerForSendChangeCardMailService,
        CustomerRepository $customerRepository,
        EntityManagerInterface $entityManager,
        ConfigRepository $configRepository
    ) {
        parent::__construct();
        $this->changeCardNotificationMailService = $changeCardNotificationMailService;
        $this->isActiveRegularService = $isActiveRegularService;
        $this->getCustomerForSendChangeCardMailService = $getCustomerForSendChangeCardMailService;
        $this->customerRepository = $customerRepository;
        $this->entityManager = $entityManager;
        $this->configRepository = $configRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Send a notification email to members who are notified that their credit card has expired.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isActiveRegularService->isActive()) {
            $this->io->text('=== Regular setting is not Active. ===');

            return 0;
        }
        $this->io->text('=== SendChangeCardMailCommand start. ===');

        logs('gmo_epsilon')->info('=== SendChangeCardMailCommand Start ===');
        $today = new \DateTime();
        $customerIds = $this->getCustomerForSendChangeCardMailService->get();

        /** @var Config $Config */
        $Config = $this->configRepository->find(1);

        foreach ($customerIds as $ids) {
            foreach ($ids as $id) {
                /** @var Customer $Customer */
                $Customer = $this->customerRepository->find($id);
                // クレジット有効期限切れの会員かチェック
                $expireDate = $Customer->getGmoEpsilonCreditCardExpirationDate();
                $expireDate->modify('- '.$Config->getCardExpirationNotificationDays().'day');

                // クレジットカード有効期限通知範囲内で有効期限切れ通知メール送信済みの場合は処理を行わない
                $cardChangeRequestMailSendDate = $Customer->getCardChangeRequestMailSendDate();
                if (!empty($cardChangeRequestMailSendDate) && $expireDate < $cardChangeRequestMailSendDate) {
                    logs('gmo_epsilon')->info('=== Customer: '.$Customer->getId().' already sent mail. ===');
                    continue;
                }
                // クレジットカード有効期限通知日が過ぎている場合はメールを送信
                if ($expireDate < $today) {
                    $expireDate = $Customer->getGmoEpsilonCreditCardExpirationDate();
                    $expireDate->modify('+ '.$Config->getCardExpirationNotificationDays().'day');
                    $this->changeCardNotificationMailService->sendMail($Customer);
                    $this->io->text('=== Customer id: '.$Customer->getId().' send. ===');
                    logs('gmo_epsilon')->info('=== Customer: '.$Customer->getId().' -- ExpireDate : '.$expireDate->format('Y/m/d').' send. ===');
                    $Customer->setCardChangeRequestMailSendDate(new \DateTime());
                    $this->entityManager->persist($Customer);
                    $this->entityManager->flush();
                }
            }
        }

        logs('gmo_epsilon')->info('=== SendChangeCardMailCommand End ===');
        $this->io->text('=== SendChangeCardMailCommand end. ===');

        return 1;
    }
}
