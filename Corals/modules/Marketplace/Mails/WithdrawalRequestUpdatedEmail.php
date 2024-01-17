<?php

namespace Corals\Modules\Marketplace\Mails;

use Corals\Modules\Payment\Common\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawalRequestUpdatedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $body, $subject, $transaction;

    /**
     * WithdrawalRequestedEmail constructor.
     * @param $order
     * @param null $subject
     * @param null $body
     */
    public function __construct(Transaction $transaction, $subject = null, $body = null)
    {
        $this->transaction = $transaction;
        $this->body = $body;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('Marketplace::mails.withdrawal_request');
    }
}
