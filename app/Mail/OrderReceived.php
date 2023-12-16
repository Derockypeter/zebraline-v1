<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
    public $product;
    public $shipping;
    public $orderID;
    public $socialHandles;
    public $mailHead;
    public $mailSubtitle;
    public $brandNDomain;
    public $subject;


 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($product, $shipping, $orderId, $socialHandles, $mailHead, $mailSubtitle, $brandNDomain, $subject)
    {
        $this->product = $product;
        $this->shipping = $shipping;
        $this->orderID = $orderId;
        $this->socialHandles = $socialHandles;
        $this->mailHead = $mailHead;
        $this->mailSubtitle = $mailSubtitle;
        $this->brandNDomain = $brandNDomain;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.orderpaid');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            // from: new Address("inquiry@".$this->brandNDomain[0], $this->brandNDomain[1]),
            from: new Address("admin@zebraline.ai", $this->brandNDomain[1]),
            subject: $this->subject,
        );
    }
}