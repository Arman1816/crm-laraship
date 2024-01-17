<?php


namespace Corals\Modules\Marketplace\Console\Commands;

use Corals\Modules\Marketplace\Jobs\HandleOrdersWithPayouts;
use Corals\Modules\Marketplace\Models\Store;
use Corals\Settings\Facades\Settings;
use Illuminate\Console\Command;

class TransferScheduledPayouts extends Command
{
    protected $signature = 'marketplace:transfer-payout';
    protected $description = 'Marketplace: Transfer Scheduled Payouts ';

    public function handle()
    {
        $this->line('Start TransferScheduledPayouts command');

        $transactions_age = Settings::get('marketplace_payout_min_transaction_age', 0);
        $payout_model = Settings::get('marketplace_payout_payout_mode', '');
        $last_payout_time = \Carbon\Carbon::now()->subDays($transactions_age);

        if ($payout_model != "periodic") {
            $this->line('Scheduled Transfer is not enabled, Exiting..');
            return;
        }
        $stores = Store::all();
        foreach ($stores as $store) {
            $this->line('Start Transfer for Store: '.$store->name);

            $last_store_payout = $store->getProperty('last_payout_date');
            if (!$last_store_payout) {
                $last_store_payout = '1970-01-01 00:00:00';
            }
            $storeOrders = $store->orders()->whereBetween('created_at', [$last_store_payout, $last_payout_time])->get();
            foreach ($storeOrders as $order) {
                $this->line('Start Payout for Order:' . $order->order_number);
                dispatch(new HandleOrdersWithPayouts($order));
            }
            $store->setProperty('last_payout_date', $last_payout_time);

        }

    }
}
