<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CartService;
use App\Services\Order\OrderService;
use App\Services\Payments\PaymentService;
use App\Services\User\PointService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected PointService $pointService
    ) {
        // Gateways are now automatically loaded via PaymentService::__construct
    }

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = $this->cartService->getCart();
        if (count($cart['items']) === 0) {
            return redirect()->route('cart.index');
        }

        return Inertia::render('Shop/Checkout', [
            'cart' => $cart,
            'paymentMethods' => $this->paymentService->getAvailableGateways(),
        ]);
    }

    /**
     * Process the checkout.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'shipping_address.name' => 'required|string',
            'shipping_address.street' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.postal_code' => 'required|string',
        ]);

        $cart = $this->cartService->getCart();
        
        // 1. Create Order
        $order = $this->orderService->createOrderFromCart(
            $cart,
            auth()->id(),
            $validated['shipping_address'],
            $validated['payment_method']
        );

        // 1.5 Deduct Points if used
        if ($order->points_used > 0) {
            $this->pointService->deductPoints(
                auth()->user(),
                $order->points_used,
                'Order point redemption: ' . $order->order_number,
                $order
            );
        }

        // 2. Clear Cart
        $this->cartService->clearCart();

        // 3. Initiate Payment
        $gateway = $this->paymentService->getGateway($validated['payment_method']);
        $paymentResult = $gateway->purchase($order);

        // 4. Return redirect info to frontend
        return Inertia::location($paymentResult['redirect_url']);
    }
}
