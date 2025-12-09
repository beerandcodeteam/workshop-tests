<?php

namespace App\Services;

use App\Jobs\SendOrderPendingEmail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\OrderStatus;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\ApiErrorException;

class CheckoutService
{
    public function processPayment(User $user, Product $product, string $paymentMethodId): array
    {
        try {
            $amount = (int) ($product->price);

            $paymentIntent = $user->pay($amount, [
                'payment_method' => $paymentMethodId,
            ]);

            $order = DB::transaction(function () use ($user, $product, $paymentIntent, $amount) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => $amount,
                    'status' => OrderStatus::Pending,
                    'payment_id' => $paymentIntent->id,
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $amount,
                ]);

                return $order;
            });

            SendOrderPendingEmail::dispatch($order);

            if ($paymentIntent->status === 'succeeded') {
                $order->update(['status' => OrderStatus::Paid]);

                return [
                    'success' => true,
                    'status' => 'success',
                    'payment_id' => $paymentIntent->id,
                    'client_secret' => null,
                ];
            }

            if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_confirmation') {
                return [
                    'success' => true,
                    'status' => 'requires_action',
                    'payment_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret,
                ];
            }

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Erro ao processar pagamento.',
                'payment_id' => $paymentIntent->id,
                'http_status' => 422,
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
                'payment_id' => null,
                'http_status' => 422,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Erro inesperado ao processar pagamento.',
                'payment_id' => null,
                'http_status' => 500,
            ];
        }
    }

    public function getOrderForSuccess(string $paymentId, int $userId): Order
    {
        return Order::where('payment_id', $paymentId)
            ->where('user_id', $userId)
            ->with(['orderItems.product', 'user'])
            ->firstOrFail();
    }

    public function getOrderForFailed(string $paymentId, int $userId): ?Order
    {
        return Order::where('payment_id', $paymentId)
            ->where('user_id', $userId)
            ->with(['orderItems.product'])
            ->firstOrFail();
    }
}
