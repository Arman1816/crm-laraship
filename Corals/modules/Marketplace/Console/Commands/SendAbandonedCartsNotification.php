<?php


namespace Corals\Modules\Marketplace\Console\Commands;

use Corals\Modules\Marketplace\Models\UserCart;
use Corals\Settings\Facades\Settings;
use Illuminate\Console\Command;

class SendAbandonedCartsNotification extends Command
{
    protected $signature = 'marketplace:send-abandoned-carts-notification';
    protected $description = 'Marketplace: Send Abandoned Carts notification';

    public function handle()
    {
        $this->line('Start SendAbandonedCartsNotification command');

        $checkPeriod = Settings::get('marketplace_general_abandoned_cart_email_after', 0);

        if (!$checkPeriod) {
            $this->line('Check period is 0, nothing to do;');
            return;
        }

        $carts = UserCart::query()->where('abandoned_email_sent', false)
            ->where('created_at', '<=', now()->subHours($checkPeriod))
            ->groupBy('email')->get();

        $this->line('Abandoned carts count: ' . $carts->count());

        foreach ($carts as $cart) {
            event('notifications.marketplace.abandoned_cart', ['cart' => $cart]);

            UserCart::query()->where('email', $cart->email)
                ->update(['abandoned_email_sent' => true]);
        }
    }
}
