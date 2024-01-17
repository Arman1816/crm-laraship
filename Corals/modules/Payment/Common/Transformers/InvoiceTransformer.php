<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Payment\Common\Models\Invoice;
use Illuminate\Support\Facades\URL;

class InvoiceTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('payment_common.models.invoice.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Invoice $invoice
     * @return array
     * @throws \Throwable
     */
    public function transform(Invoice $invoice)
    {
        $currency = strtoupper($invoice->currency);

        $levels = [
            'pending' => 'info',
            'paid' => 'success',
            'failed' => 'danger',
            'cancelled' => 'Cancelled'
        ];

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

        $publicURL = URL::signedRoute('publicInvoicePayment', ['invoice' => $invoice->hashed_id]);
        $transformedArray = [
            'id' => $invoice->id,
            'checkbox' => $this->generateCheckboxElement($invoice),
            'status' => $invoice->status ? formatStatusAsLabels($invoice->status,
                ['level' => $levels[$invoice->status], 'text' => trans('Payment::labels.invoice.' . $invoice->status)]) : "-",
            'is_sent' => $invoice->is_sent ? '<i class="fa fa-check text-success"></i>' : '-',
            'code' => '<a href="' . $invoice->getShowURL() . '">' . $invoice->code . '</a>',
            'currency' => $currency,
            'customer' => $customer,
            'public_link' => $publicURL,
            'public_link_copy' => generateCopyToClipBoard($invoice->hashed_id, $publicURL,
                trans('Payment::labels.invoice.public_link')),
            'billing_address' => $billing_address,
            'email' => $email,
            'description' => $invoice->description ? generatePopover($invoice->description) : '-',
            'invoice_date' => format_date($invoice->invoice_date),
            'due_date' => format_date($invoice->due_date),
            'sub_total' => \Payments::currency_convert($invoice->sub_total, null, $currency, true),
            'total' => \Payments::currency_convert($invoice->total, null, $currency, true),
            'user_id' => $invoice->user ? "<a href='" . url('users/' . $invoice->user->hashed_id) . "'> {$invoice->user->full_name}</a>" : "-",
            'invoicable_type' => class_basename($invoice->invoicable_type),
            'invoicable_id' => $invoice->invoicable ? $invoice->invoicable->getInvoiceReference() : '-',
            'created_at' => format_date($invoice->created_at),
            'updated_at' => format_date($invoice->updated_at),
            'action' => $this->actions($invoice)
        ];

        return parent::transformResponse($transformedArray);
    }
}
