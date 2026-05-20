<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class BankTransferGateway implements PaymentGatewayInterface
{
    protected array $config = [];

    public function getIdentifier(): string
    {
        return 'bank_transfer';
    }

    public function getName(): string
    {
        return __('Bank Transfer (銀行振込)');
    }

    public function initialize(array $config): void
    {
        $this->config = $config;
    }

    public function getConfigSchema(): array
    {
        return [
            [
                'key' => 'bank_name',
                'label' => __('Bank Name'),
                'type' => 'text',
                'placeholder' => __('e.g. Mizuho Bank')
            ],
            [
                'key' => 'branch_name',
                'label' => __('Branch Name'),
                'type' => 'text',
                'placeholder' => __('e.g. Shinjuku Branch')
            ],
            [
                'key' => 'account_type',
                'label' => __('Account Type'),
                'type' => 'select',
                'options' => [
                    'savings' => __('Savings (普通)'),
                    'checking' => __('Checking (当座)')
                ]
            ],
            [
                'key' => 'account_number',
                'label' => __('Account Number'),
                'type' => 'text',
                'placeholder' => '1234567'
            ],
            [
                'key' => 'account_holder',
                'label' => __('Account Holder Name'),
                'type' => 'text',
                'placeholder' => __('E-Commerce Inc.')
            ],
            [
                'key' => 'instructions',
                'label' => __('Payment Instructions'),
                'type' => 'text',
                'help' => __('Shown to customers during checkout and in order emails.')
            ]
        ];
    }

    public function purchase(Order $order): array
    {
        // For Bank Transfer, we provide a success URL
        // Instructions are usually shown on the thank you page or sent via email
        return [
            'redirect_url' => route('home', ['success_from' => 'bank_transfer', 'order_id' => $order->id]),
        ];
    }

    public function handleCallback(Request $request, Order $order): bool
    {
        return true;
    }

    public function refund(Order $order): bool
    {
        return true;
    }
}
