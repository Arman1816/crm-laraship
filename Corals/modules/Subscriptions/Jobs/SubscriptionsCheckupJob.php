<?php

namespace Corals\Modules\Subscriptions\Jobs;

use Corals\Modules\Payment\Facades\Payments;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\Modules\Subscriptions\Services\SubscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionsCheckupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * SubscriptionsCheckupJob constructor.
     * @param SubscriptionService $subscriptionService
     */
    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $selfManagedGateways = array_keys(Payments::getAvailableGateways('subscription_self_managed'));

        Subscription::query()
            ->where('status', 'active')
            ->whereRaw("JSON_EXTRACT(extras, '$.notify_end_subscription_sent') is null")
            ->whereIn('gateway', $selfManagedGateways)
            ->chunk(100, function ($subscriptions) {
                foreach ($subscriptions as $subscription) {
                    $remainingDays = $subscription->remainingDays();

                    $notifyBeforeDays = $subscription->plan->notify_end_subscription_before;

                    if ($remainingDays < 1) {
                        $this->subscriptionExpired($subscription);
                        continue;
                    }

                    if (!$notifyBeforeDays || $subscription->getProperty('notify_end_subscription_sent')) {
                        continue;
                    }

                    if ($notifyBeforeDays >= $remainingDays) {
                        continue;
                    }

                    $this->notifySubscription($subscription);
                }
            });
    }

    /**
     * @param $subscription
     */
    protected function notifySubscription($subscription): void
    {
        //notify subscription
        $subscription->setProperty('notify_end_subscription_sent', true);

        event('notifications.subscription.subscription_renewal_notification',
            ['user' => $subscription->user, 'subscription' => $subscription]);

        $this->subscriptionService->renewSubscription($subscription);
    }

    /**
     * @param $subscription
     */
    protected function subscriptionExpired($subscription): void
    {
        $this->subscriptionService->expireSubscription($subscription);
    }
}
