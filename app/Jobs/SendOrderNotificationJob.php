<?php

namespace App\Jobs;

use App\Mail\OrderNotificationMail;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Store $store,
        public Collection $items
    ) {}

    public function handle(): void
    {
        Mail::to($this->store->user->email)
            ->send(new OrderNotificationMail($this->order, $this->store, $this->items));
    }

    // Retry failed jobs
    public int $tries = 3;
    public int $backoff = 60; // Wait 60 seconds between retries
}