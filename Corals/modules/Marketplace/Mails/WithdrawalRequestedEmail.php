<?php

namespace Corals\Modules\Marketplace\Mails;

use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawalRequestedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $body, $subject, $transaction;

    /**
     * WithdrawalRequestedEmail constructor.
     * @param User $user
     * @param $order
     * @param null $subject
     * @param null $body
     */
    public function __construct(User $user, Transaction $transaction, $subject = null, $body = null)
    {
        $this->user = $user;
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
