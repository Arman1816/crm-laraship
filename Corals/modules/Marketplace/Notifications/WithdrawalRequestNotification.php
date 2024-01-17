<?php

namespace Corals\Modules\Marketplace\Notifications;

use Corals\Modules\Marketplace\Mails\OrderReceivedEmail;
use Corals\Modules\Marketplace\Mails\WithdrawalRequestedEmail;
use Corals\User\Communication\Classes\CoralsBaseNotification;

class WithdrawalRequestNotification extends CoralsBaseNotification
{
    /**
     * @param null $subject
     * @param null $body
     * @return WithdrawalRequestedEmail|null
     */
    protected function mailable($subject = null, $body = null)
    {
        return new WithdrawalRequestedEmail($this->data['user'], $this->data['transaction'], $subject, $body);
    }

    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        return [];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $user = $this->data['user'];

        return [
            'user_name' => $user->full_name,
            'transaction_url' => url(config('payment_common.models.transaction.resource_url') . '/' . $this->data['transaction']->hashed_id . '/edit'),
            'transaction_amount' => \Payments::currency($this->data['transaction']->amount * -1),
            'transaction_notes' => $this->data['transaction']->notes,
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'user_name' => 'Transaction user name',
            'transaction_url' => 'Transaction Link',
            'transaction_amount' => 'Transaction Amount',
            'transaction_notes' => 'Transaction Notes'
        ];
    }
}
