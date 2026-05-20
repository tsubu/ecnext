<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use Exception;

class PaymentService
{
    /**
     * @var array<string, PaymentGatewayInterface>
     */
    protected array $gateways = [];

    /**
     * Map of provider keys to their gateway classes.
     */
    protected array $gatewayRegistry = [
        'stripe' => \App\Services\Payments\StripeGateway::class,
        'paypal' => \App\Services\Payments\PayPalGateway::class,
        'amazon_pay' => \App\Services\Payments\AmazonPayGateway::class,
        'cod' => \App\Services\Payments\CodGateway::class,
        'bank_transfer' => \App\Services\Payments\BankTransferGateway::class,
    ];

    public function __construct()
    {
        $this->bootActiveGateways();
    }

    /**
     * Load active gateways from database.
     */
    protected function bootActiveGateways(): void
    {
        try {
            // Use static call to avoid infinite loop or use a flag
            $providers = \App\Models\PaymentProvider::where('is_active', true)->get();
            
            foreach ($providers as $provider) {
                if (isset($this->gatewayRegistry[$provider->provider_key])) {
                    $className = $this->gatewayRegistry[$provider->provider_key];
                    $gateway = new $className();
                    $gateway->initialize($provider->config ?? []);
                    $this->registerGateway($gateway);
                }
            }
        } catch (\Exception $e) {
            // Silently fail during migrations or if table doesn't exist yet
        }
    }

    /**
     * Register a payment gateway.
     */
    public function registerGateway(PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$gateway->getIdentifier()] = $gateway;
    }

    /**
     * Get a gateway by its identifier.
     */
    public function getGateway(string $identifier): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$identifier])) {
            throw new Exception("Payment gateway [{$identifier}] is not registered.");
        }

        return $this->gateways[$identifier];
    }

    /**
     * Get all registered gateways for selection.
     */
    public function getAvailableGateways(): array
    {
        return array_map(fn($g) => [
            'id' => $g->getIdentifier(),
            'name' => $g->getName(),
        ], $this->gateways);
    }

    /**
     * Get the full registry of available gateway classes.
     */
    public function getGatewayRegistry(): array
    {
        return $this->gatewayRegistry;
    }
}
