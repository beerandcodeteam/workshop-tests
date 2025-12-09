<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutPaymentRequest;
use App\Models\Product;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutService $checkoutService
    ) {}

    public function show(Product $product): View
    {
        return view('checkout.form', [
            'product' => $product,
        ]);
    }

    public function pay(CheckoutPaymentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        $result = $this->checkoutService->processPayment(
            $request->user(),
            $product,
            $validated['payment_method_id']
        );

        if (! $result['success']) {
            $response = [
                'status' => $result['status'],
                'message' => $result['message'],
            ];

            if ($result['payment_id']) {
                $response['redirect_url'] = route('checkout.failed', ['payment_id' => $result['payment_id']]);
            } else {
                $response['redirect_url'] = route('checkout.form', ['product' => $validated['product_id']]);
            }

            return response()->json($response, $result['http_status']);
        }

        if ($result['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'payment_id' => $result['payment_id'],
                'redirect_url' => route('checkout.success', ['payment_id' => $result['payment_id']]),
            ]);
        }

        if ($result['status'] === 'requires_action') {
            return response()->json([
                'status' => 'requires_action',
                'payment_id' => $result['payment_id'],
                'client_secret' => $result['client_secret'],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erro ao processar pagamento.',
        ], 500);
    }

    public function success(Request $request, string $payment_id): View
    {
        $order = $this->checkoutService->getOrderForSuccess(
            $payment_id,
            $request->user()->id
        );

        return view('checkout.success', [
            'order' => $order,
        ]);
    }

    public function failed(Request $request, string $payment_id): View
    {
        $order = $this->checkoutService->getOrderForFailed(
            $payment_id,
            $request->user()->id
        );

        return view('checkout.failed', [
            'order' => $order,
            'payment_id' => $request->query('payment_id'),
        ]);
    }
}
