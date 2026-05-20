<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class PayPalGateway implements PaymentGatewayInterface
{
    protected array $config = [];

    public function getIdentifier(): string
    {
        return 'paypal';
    }

    public function getName(): string
    {
        return __('PayPal');
    }

    public function initialize(array $config): void
    {
        $this->config = $config;
    }

    public function getConfigSchema(): array
    {
        return [
            [
                'key' => 'client_id',
                'label' => __('PayPal Client ID'),
                'type' => 'text',
                'is_secret' => false,
            ],
            [
                'key' => 'client_secret',
                'label' => __('PayPal Client Secret'),
                'type' => 'password',
                'is_secret' => true,
            ],
            [
                'key' => 'mode',
                'label' => __('Transaction Mode'),
                'type' => 'select',
                'options' => [
                    'sandbox' => __('Sandbox'),
                    'live' => __('Live')
                ]
            ]
        ];
    }

    public function purchase(Order $order): array
    {
        // Skeleton for PayPal Order Create API
        return [
            'redirect_url' => route('home', ['success_from' => 'paypal', 'order_id' => $order->id]),
            'paypal_order_id' => 'PAY-MOCK-998877',
        ];
    }

    public function handleCallback(Request $request, Order $order): bool
    {
        // Skeleton for PayPal Order Capture API
        return true;
    }

    public function refund(Order $order): bool
    {
        // Skeleton for PayPal Refund API
        return true;
    }
}
