<?php

namespace Corals\Modules\Payment\Common\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Payment\Common\Models\Invoice;

class InvoiceTransformer extends APIBaseTransformer
{
    /**
     * @param Invoice $invoice
     * @return array
     * @throws \Throwable
     */
    public function transform(Invoice $invoice)
    {
        $currency = strtoupper($invoice->currency);

        $customer = "";
        $email = "";
        $billing_address = "";

        if ($invoice->hasProperty('billing_address')) {
            $billing_details = $invoice->getProperty('billing_address');
            $customer = $billing_details['first_name'] . ' ' . $billing_details['last_name'];
            $email = $billing_details['email'];
            $billing_address = $invoice->display_address($billing_details);
        } elseif ($invoice->user) {
            $customer = $invoice->user->full_name;
            $email = $invoice->user->email;
            $billing_address = $invoice->user->display_address('billing');
        }

        $transformedArray = [
            'id' => $invoice->id,
            'status' => trans('Payment::labels.invoice.' . $invoice->status),
            'is_sent' => $invoice->is_sent,
            'code' => $invoice->code,
            'currency' => $currency,
            'customer' => $customer,
            'billing_address' => $billing_address,
            'email' => $email,
            'description' => $invoice->description,
            'due_date' => format_date($invoice->due_date),
            'sub_total' => \Payments::currency_convert($invoice->sub_total, null, $currency, true),
            'total' => \Payments::currency_convert($invoice->total, null, $currency, true),
            'user_id' => $invoice->user ? $invoice->user->full_name : "-",
            'invoicable_type' => class_basename($invoice->invoicable_type),
            'invoicable_id' => $invoice->invoicable ? $invoice->invoicable->getInvoiceReference() : '-',
            'created_at' => format_date($invoice->created_at),
            'updated_at' => format_date($invoice->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
