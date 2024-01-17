<?php


namespace Corals\Modules\Subscriptions\Exceptions;


class LimitReachedException extends \Exception
{
    public function __construct($subscription, $feature, $currentCycle = null)
    {
        if (empty($message)) {
            if ($currentCycle) {
                $period = $currentCycle->period;
            } else {
                $period = '';
            }

            $message = sprintf("%s %s the feature [%s] reached the limit",
                $subscription->subscription_reference, $period, $feature->name);
        }

        parent::__construct($message);
    }
}
