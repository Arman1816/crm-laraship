<?php

namespace Corals\Modules\Marketplace\Mails;

use Corals\Modules\Marketplace\Models\Order;
use Corals\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceivedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $body, $subject, $order, $options;

    /**
     * OrderReceivedEmail constructor.
     * @param User $user
     * @param Order $order
     * @param null $subject
     * @param null $body
     * @param array $options
     */
    public function __construct(User $user, Order $order, $subject = null, $body = null, $options = [])
    {
        $this->user = $user;
        $this->order = $order;
        $this->body = $body;
        $this->subject = $subject;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('Marketplace::mails.order_details');
    }
}
