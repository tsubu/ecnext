<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class CodGateway implements PaymentGatewayInterface
{
    protected array $config = [];

    public function getIdentifier(): string
    {
        return 'cod';
    }

    public function getName(): string
    {
        return __('Cash on Delivery (代引き)');
    }

    public function initialize(array $config): void
    {
        $this->config = $config;
    }

    public function getConfigSchema(): array
    {
        return [
            [
                'key' => 'cod_fee',
                'label' => __('COD Fee (JPY)'),
                'type' => 'number',
                'placeholder' => '330',
                'help' => __('The amount added to the order total for cash on delivery.')
            ],
            [
                'key' => 'min_order_total',
                'label' => __('Minimum Order Total'),
                'type' => 'number',
                'help' => __('Minimum amount required to use COD.')
            ]
        ];
    }

    public function purchase(Order $order): array
    {
        // For COD, we just complete the order process locally
        // In a real app, you might update the order status to 'awaiting_payment' or 'pending'
        return [
            'redirect_url' => route('home', ['success_from' => 'cod', 'order_id' => $order->id]),
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
