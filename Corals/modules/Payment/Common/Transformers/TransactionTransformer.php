<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Payment\Common\Models\Transaction;

class TransactionTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('payment_common.models.transaction.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Transaction $transaction
     * @return array
     * @throws \Throwable
     */
    public function transform(Transaction $transaction)
    {
        $invoice_resource_url = config('payment_common.models.invoice.resource_url');

        $levels = [
            'pending' => 'info',
            'processing' => 'processing',
            'completed' => 'success',
            'failed' => 'danger',
            'cancelled' => 'warning'
        ];

        $payment_currency = strtoupper($transaction->paid_currency);

        $invoice_link = $transaction->invoice ? '<a target="_blank" href="' . url($invoice_resource_url . '/' . $transaction->invoice->hashed_id . '/download') . '">' . $transaction->invoice->code . '</a>' : '-';

        $transformedArray = [
            'id' => $transaction->id,
            'code' => $transaction->code,
            'checkbox' => $this->generateCheckboxElement($transaction),
            'invoice' => $invoice_link,
            'status' => formatStatusAsLabels($transaction->status, [
                'level' => $levels[$transaction->status],
                'text' => trans('Payment::status.transaction.' . $transaction->status)
            ]),
            'source' => $transaction->sourcable ? $transaction->sourcable->getTransactionSource() : '-',
            'type' => trans('Payment::attributes.transaction.types.' . $transaction->type),
            'exception' => $transaction->exception ? generatePopover("'" . $transaction->getRawOriginal('exception') . "'") : '-',
            'gateway' => $transaction->gateway,
            'paid_amount' => $transaction->paid_amount ? \Currency::format($transaction->paid_amount,
                $payment_currency) : '-',
            'reference' => $transaction->reference,
            'amount' => \Payments::admin_currency($transaction->amount),
            'notes' => generatePopover("'" . $transaction->getRawOriginal('notes') . "'"),
            'processed' => $transaction->processed ? '<i class="fa fa-check text-success"></i>' : '-',
            'created_at' => format_date($transaction->created_at),
            'updated_at' => format_date($transaction->updated_at),
            'action' => $this->actions($transaction)
        ];

        return parent::transformResponse($transformedArray);
    }
}
