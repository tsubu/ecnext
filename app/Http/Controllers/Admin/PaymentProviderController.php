<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProvider;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;

class PaymentProviderController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display a listing of the payment providers.
     */
    public function index()
    {
        $providers = PaymentProvider::all();
        return view('admin.marketing.payments.index', compact('providers'));
    }

    /**
     * Show the form for editing the provider configuration.
     */
    public function edit(PaymentProvider $provider)
    {
        // We need to create an instance of the gateway to get its schema
        // This is a bit hacky but works for a plugin system
        $gatewayClass = null;
        
        $registry = $this->paymentService->getGatewayRegistry();

        if (!isset($registry[$provider->provider_key])) {
            abort(404, "Gateway class not found for {$provider->provider_key}");
        }

        $gateway = new $registry[$provider->provider_key]();
        $schema = $gateway->getConfigSchema();

        return view('admin.marketing.payments.edit', compact('provider', 'schema'));
    }

    /**
     * Update the provider configuration and status.
     */
    public function update(Request $request, PaymentProvider $provider)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
            'config' => 'nullable|array',
        ]);

        $provider->update([
            'is_active' => $request->has('is_active'),
            'config' => $request->config ?? [],
        ]);

        return redirect()->route('admin.payments.index')->with('success', __('Payment provider updated successfully.'));
    }
}
