<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'provider_key' => 'stripe',
                'name' => 'Stripe (Credit Card)',
                'is_active' => true,
                'config' => [
                    'publishable_key' => '',
                    'secret_key' => '',
                    'webhook_secret' => '',
                    'mode' => 'test'
                ]
            ],
            [
                'provider_key' => 'paypal',
                'name' => 'PayPal',
                'is_active' => true,
                'config' => [
                    'client_id' => '',
                    'client_secret' => '',
                    'mode' => 'sandbox'
                ]
            ],
            [
                'provider_key' => 'amazon_pay',
                'name' => 'Amazon Pay',
                'is_active' => false,
                'config' => []
            ],
            [
                'provider_key' => 'cod',
                'name' => 'Cash on Delivery (代引き)',
                'is_active' => true,
                'config' => [
                    'fee' => '0'
                ]
            ],
            [
                'provider_key' => 'bank_transfer',
                'name' => 'Bank Transfer (銀行振込)',
                'is_active' => true,
                'config' => [
                    'bank_name' => '',
                    'branch_name' => '',
                    'account_type' => 'savings',
                    'account_number' => '',
                    'account_holder' => ''
                ]
            ],
        ];

        foreach ($providers as $provider) {
            PaymentProvider::updateOrCreate(
                ['provider_key' => $provider['provider_key']],
                $provider
            );
        }
    }
}
