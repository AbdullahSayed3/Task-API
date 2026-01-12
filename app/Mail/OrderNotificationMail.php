<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Store $store,
        public Collection $items
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Order #{$this->order->id} - {$this->store->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-notification',
            with: [
                'storeName' => $this->store->name,
                'orderNumber' => $this->order->id,
                'customerName' => $this->order->customer_name,
                'customerEmail' => $this->order->customer_email,
                'customerPhone' => $this->order->customer_phone,
                'items' => $this->items,
                'total' => $this->items->sum(fn($item) => $item->price * $item->quantity),
            ]
        );
    }
}