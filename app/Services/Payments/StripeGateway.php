<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class StripeGateway implements PaymentGatewayInterface
{
    protected array $config = [];

    public function getIdentifier(): string
    {
        return 'stripe';
    }

    public function getName(): string
    {
        return __('Stripe (Credit Card)');
    }

    public function initialize(array $config): void
    {
        $this->config = $config;
    }

    public function getConfigSchema(): array
    {
        return [
            [
                'key' => 'publishable_key',
                'label' => __('Stripe Publishable Key'),
                'type' => 'text',
                'placeholder' => 'pk_test_...',
                'help' => __('Required for client-side tokenization.')
            ],
            [
                'key' => 'secret_key',
                'label' => __('Stripe Secret Key'),
                'type' => 'text',
                'is_secret' => true,
                'placeholder' => 'sk_test_...',
                'help' => __('Used for server-side API calls.')
            ],
            [
                'key' => 'webhook_secret',
                'label' => __('Webhook Signing Secret'),
                'type' => 'text',
                'is_secret' => true,
                'placeholder' => 'whsec_...',
                'help' => __('Verifies authenticity of incoming webhooks.')
            ],
            [
                'key' => 'mode',
                'label' => __('Transaction Mode'),
                'type' => 'select',
                'options' => [
                    'test' => __('Test Mode'),
                    'live' => __('Live Mode')
                ]
            ]
        ];
    }

    public function purchase(Order $order): array
    {
        // Skeleton for Stripe Checkout Session creation
        return [
            'redirect_url' => route('home', ['success_from' => 'stripe', 'order_id' => $order->id]),
            'payment_intent_id' => 'pi_mock_12345',
        ];
    }

    public function handleCallback(Request $request, Order $order): bool
    {
        // Skeleton for Stripe Webhook/Callback handling
        return true;
    }

    public function refund(Order $order): bool
    {
        // Skeleton for Stripe Refund API
        return true;
    }
}
