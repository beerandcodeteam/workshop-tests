<?php

namespace App\Listeners;

use App\Jobs\SendOrderFailedEmail;
use App\Jobs\SendOrderPaidEmail;
use App\Models\Order;
use App\OrderStatus;
use Laravel\Cashier\Events\WebhookReceived;

class HandleStripeWebhook
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->payload;

        if ($payload['type'] === 'payment_intent.succeeded') {
            $this->handlePaymentSucceeded($payload['data']['object']);
        }

        if ($payload['type'] === 'payment_intent.payment_failed') {
            $this->handlePaymentFailed($payload['data']['object']);
        }
    }

    protected function handlePaymentSucceeded(array $paymentIntent): void
    {
        $order = Order::where('payment_id', $paymentIntent['id'])->first();

        if (! $order) {
            return;
        }

        if ($order->status === OrderStatus::Paid) {
            return;
        }

        $order->update(['status' => OrderStatus::Paid]);

        SendOrderPaidEmail::dispatch($order);
    }

    protected function handlePaymentFailed(array $paymentIntent): void
    {
        $order = Order::where('payment_id', $paymentIntent['id'])->first();

        if (! $order) {
            return;
        }

        if ($order->status === OrderStatus::Failed) {
            return;
        }

        $order->update(['status' => OrderStatus::Failed]);

        SendOrderFailedEmail::dispatch($order);
    }
}
