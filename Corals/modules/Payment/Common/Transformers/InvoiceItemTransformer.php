<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Payment\Common\Models\InvoiceItem;

class InvoiceItemTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        parent::__construct($extras);
    }

    /**
     * @param InvoiceItem $invoiceItem
     * @return array
     * @throws \Throwable
     */
    public function transform(InvoiceItem $invoiceItem)
    {
        $invoice = $invoiceItem->invoice;

        $total = $invoiceItem->quantity * $invoiceItem->amount;

        $total = \Payments::currency_convert($total, null, $invoice->currency, true);

        $transformedArray = [
            'id' => $invoiceItem->id,
            'code' => $invoiceItem->code,
            'amount' => \Payments::currency_convert($invoiceItem->amount, null, $invoice->currency),
            'quantity' => $invoiceItem->quantity,
            'total' => $total,
            'itemable_type' => $invoiceItem->itemable_type,
            'itemable_id' => $invoiceItem->itemable_id,
            'object_reference' => $invoiceItem->object_reference,
            'description' => $invoiceItem->description,
            'created_at' => format_date($invoiceItem->created_at),
            'updated_at' => format_date($invoiceItem->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
