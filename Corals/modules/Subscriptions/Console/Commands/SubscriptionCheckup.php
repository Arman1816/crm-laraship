<?php

namespace Corals\Modules\Subscriptions\Console\Commands;

use Corals\Modules\Subscriptions\Jobs\SubscriptionsCheckupJob;
use Illuminate\Console\Command;

class SubscriptionCheckup extends Command
{
    protected $signature = 'subscription:checkup';
    protected $description = 'Subscriptions Checkup Command';


    /**
     * subscription:checkup command handler
     */
    public function handle()
    {
        dispatch_sync(new SubscriptionsCheckupJob());
    }
}
