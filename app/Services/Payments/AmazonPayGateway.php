<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class AmazonPayGateway implements PaymentGatewayInterface
{
    protected array $config = [];

    public function getIdentifier(): string
    {
        return 'amazon_pay';
    }

    public function getName(): string
    {
        return 'Amazon Pay';
    }

    public function initialize(array $config): void
    {
        $this->config = $config;
    }

    public function getConfigSchema(): array
    {
        return [
            [
                'key' => 'merchant_id',
                'label' => 'Merchant ID',
                'type' => 'text',
                'placeholder' => '...',
            ],
            [
                'key' => 'public_key_id',
                'label' => 'Public Key ID',
                'type' => 'text',
                'placeholder' => 'SANDBOX-...',
            ],
            [
                'key' => 'store_id',
                'label' => 'Store ID',
                'type' => 'text',
                'placeholder' => 'amzn1.application-oa2-client....',
            ],
            [
                'key' => 'mode',
                'label' => 'Sandbox / Live',
                'type' => 'select',
                'options' => [
                    'sandbox' => 'Sandbox',
                    'live' => 'Live'
                ]
            ]
        ];
    }

    public function purchase(Order $order): array
    {
        // Skeleton for Amazon Pay Checkout Session
        return [
            'redirect_url' => route('home', ['success_from' => 'amazonpay', 'order_id' => $order->id]),
            'amazon_checkout_session_id' => 'AMZ-MOCK-SESSION-445566',
        ];
    }

    public function handleCallback(Request $request, Order $order): bool
    {
        // Skeleton for Amazon Pay Complete Checkout
        return true;
    }

    public function refund(Order $order): bool
    {
        // Skeleton for Amazon Pay Refund API
        return true;
    }
}
