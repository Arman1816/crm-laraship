<?php


namespace Corals\Modules\Marketplace\Jobs;


use Corals\Modules\Payment\Classes\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleOrdersWithPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $store = $this->order->store;
        $billingDetails = $this->order->billing;
        $gateway = data_get($billingDetails, 'gateway');
        $paymentReference = data_get($this->order->billing, 'payment_reference');

        if (!$gateway || !$paymentReference) {
            return;
        }

        $user = $store->user;

        if (!$user) {
            return;
        }

        $gatewayStatus = $user->getGatewayStatus($gateway, 'AccountConnect', true)->first();

        if (!$gatewayStatus || $gatewayStatus->status !== 'PAYOUTS_ENABLED') {
            return;
        }

        $invoice = $this->order->invoice;

        if (!$invoice) {
            return;
        }

        $amount = $invoice->transactions()->where('status', '!=', 'cancelled')->sum('amount');

        if ($amount <= 0) {
            return;
        }

        try {
            $payments = new Payments($gateway);

            $response = $payments->createTransfer([
                'sourceTransaction' => $paymentReference,
                'amount' => $amount,
                'currency' => $invoice->currency,
                'destination' => $gatewayStatus->object_reference,
                'metadata' => [
                    'order_id' => $this->order->id,
                    'invoice_id' => $invoice->id,
                ]
            ]);

            $payoutId = data_get($response, 'id');

            $user->transactions()->create([
                'invoice_id' => $invoice->id,
                'sourcable_id' => $this->order->id,
                'sourcable_type' => getMorphAlias($this->order),
                'amount' => -1 * $amount,
                'reference' => is_array($payoutId) ? json_encode($payoutId) : $payoutId,
                'transaction_date' => now(),
                'type' => 'payout',
                'status' => 'completed',
                'notes' => $gateway . ' Payout',
                'extra' => [
                    'gateway' => $gateway,
                ]
            ]);

            unset($billingDetails['payout_exception']);

            $billingDetails['payout_reference'] = $payoutId;

            $this->order->update([
                'billing' => $billingDetails,
            ]);
        } catch (\Exception $exception) {
            report($exception);
            $billingDetails['payout_exception'] = $exception->getMessage();

            $this->order->update([
                'billing' => $billingDetails,
            ]);
        }
    }
}
