<?php

namespace Corals\Modules\Payment\Common\Notifications;


use Corals\User\Communication\Classes\CoralsBaseNotification;

class InvoiceNotification extends CoralsBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $invoice = $this->data['invoice'];

        if ($invoice->user) {
            return $invoice->user;
        }
        return [];
    }

    /**
     * @return mixed
     */
    public function getOnDemandNotificationNotifiables()
    {
        $invoice = $this->data['invoice'];

        if (!$invoice->user) {
            if ($invoice->hasProperty('billing_address')) {
                $billing_details = $invoice->getProperty('billing_address');
                return [
                    'mail' => $billing_details['email']
                ];
            }
        }
        return [];
    }


    protected function getAttachments()
    {
        $invoice = $this->data['invoice'];

        $pdfFileName = $invoice->getPdfFileName(true);

        $pdf = \PDF::loadView('Payment::invoices.invoice', ['invoice' => $invoice, 'PDF' => true]);

        $pdf->save($pdfFileName);

        $invoice->update(['is_sent' => true]);

        return $pdfFileName;
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $invoice = $this->data['invoice'];
        $user = $invoice->user;

        return [
            'user_name' => $user->name,
            'invoice_code' => $invoice->code,
            'invoicable_type' => $invoice->invoicable_type ? class_basename($invoice->invoicable_type) : '-',
            'invoicable_id' => $invoice->invoicable ? $invoice->invoicable->getInvoiceReference() : '-',
            'invoicable_identifier' => $invoice->invoicable_type ? $invoice->invoicable->getIdentifier() : '-',
            'invoice_url' => $invoice->getShowUrl(),
            'public_link' => $invoice->present('public_link')
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'user_name' => 'User Name',
            'invoice_code' => 'Invoice Code',
            'invoicable_type' => 'Invoice Object Type',
            'invoicable_id' => 'Invoice Object Id',
            'invoicable_identifier' => 'Invoice Object Identifier',
            'invoice_url' => 'Invoice Show URL',
            'public_link' => "Public Invoice Link"
        ];
    }

}
