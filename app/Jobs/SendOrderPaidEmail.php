<?php

namespace App\Jobs;

use App\Mail\OrderPaidMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderPaidEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Order $order
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->order->user->email)
            ->send(new OrderPaidMail($this->order));
    }
}
