<?php

namespace App\Contracts\Payments;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Get the unique identifier for this gateway.
     */
    public function getIdentifier(): string;

    /**
     * Get the display name for this gateway.
     */
    public function getName(): string;

    /**
     * Initialize the purchase process and return data for frontend (e.g. redirect URL).
     */
    public function purchase(Order $order): array;

    /**
     * Handle the response/callback from the payment provider.
     */
    public function handleCallback(Request $request, Order $order): bool;

    /**
     * refunding a given order.
     */
    public function refund(Order $order): bool;

    /**
     * Initialize the gateway with configuration from the database.
     */
    public function initialize(array $config): void;

    /**
     * Get the configuration schema for the admin UI.
     */
    public function getConfigSchema(): array;
}
