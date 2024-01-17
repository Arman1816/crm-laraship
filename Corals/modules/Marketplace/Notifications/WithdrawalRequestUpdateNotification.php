<?php

namespace Corals\Modules\Marketplace\Notifications;

use Corals\Modules\Marketplace\Mails\OrderReceivedEmail;
use Corals\Modules\Marketplace\Mails\WithdrawalRequestedEmail;
use Corals\Modules\Marketplace\Mails\WithdrawalRequestUpdatedEmail;
use Corals\User\Communication\Classes\CoralsBaseNotification;

class WithdrawalRequestUpdateNotification extends CoralsBaseNotification
{
    /**
     * @param null $subject
     * @param null $body
     * @return WithdrawalRequestUpdatedEmail|null
     */
    protected function mailable($subject = null, $body = null)
    {
        return new WithdrawalRequestUpdatedEmail($this->data['transaction'], $subject, $body);
    }

    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $transaction_owner = $this->data['transaction']->owner;
        return [$transaction_owner];


    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $user = $this->data['transaction']->owner;

        return [
            'user_name' => $user->full_name,
            'transaction_url' => url(config('marketplace.models.transaction.resource_url')),
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'user_name' => 'Transaction user name',
        ];
    }
}
