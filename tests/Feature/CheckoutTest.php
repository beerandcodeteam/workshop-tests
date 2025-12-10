<?php

use App\Jobs\SendOrderPendingEmail;

it('process succeed payment and create order', function () {
    Queue::fake();

    $user = \App\Models\User::factory()->create();
    $product = \App\Models\Product::factory()->create([
        'price' => 123
    ]);

    $payment = Mockery::mock(\Laravel\Cashier\Payment::class);
    $payment->id = 'pi_123';
    $payment->status = 'succeeded';
    $payment->client_secret = null;

    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldReceive('pay')
        ->once()
        ->withArgs(function ($ammount, $options) {
            return $ammount === 123 && $options['payment_method'] === 'pm_fake';
        })->andReturn($payment);

    $service = app(\App\Services\CheckoutService::class);

    $result = $service->processPayment($userMock, $product, 'pm_fake');

    expect($result)->toMatchArray([
        'success' => true,
        'status' => 'success',
        'payment_id' => $payment->id,
        'client_secret' => null,
    ]);

    $order = \App\Models\Order::first();
    expect($order)->not->toBeNull()
        ->and($order->user_id)->toBe($user->id)
        ->and($order->total)->toBe($product->price)
        ->and($order->status)->toBe(\App\OrderStatus::Paid);

    $orderItem = \App\Models\OrderItem::first();
    expect($orderItem)->not->toBeNull()
        ->and($orderItem->order_id)->toBe($order->id)
        ->and($orderItem->product_id)->toBe($product->id)
        ->and($orderItem->quantity)->toBe(1)
        ->and($orderItem->price)->toBe($product->price);


    Queue::assertPushed(SendOrderPendingEmail::class, function ($job) use ($order) {
        return $job->order->id === $order->id;
    });
});

test('checkout pay request validates required fields', function () {
     $user = \App\Models\User::factory()->create();

     $this->actingAs($user)
         ->postJson(route('checkout.pay'), [])
         ->assertUnprocessable()
         ->assertJsonValidationErrors([
             'product_id',
             'address',
             'payment_method_id'
         ]);
});
