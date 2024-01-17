<?php

namespace Corals\Modules\Payment\Common\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Traits\ModelUniqueCode;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Payment\Common\Events\InvoicePaidEvent;
use Corals\User\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;


class Invoice extends BaseModel
{
    use PresentableTrait, LogsActivity, ModelUniqueCode, ModelPropertiesTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'payment_common.models.invoice';

    protected static $logAttributes = [];

    protected $guarded = ['id'];

    protected $casts = [
        'extras' => 'array',
        'is_sent' => 'boolean',
        'properties' => 'json'

    ];


    public function display_address($address)
    {
        $display_address = "";

        if (isset($address['address_1'])) {
            $display_address .= $address['address_1'] . "<br>";
        }

        if (isset($address['address_2'])) {
            $display_address .= $address['address_2'] . "<br>";
        }


        if (isset($address['city'])) {
            $display_address .= $address['city'];
        }

        if (isset($address['state'])) {
            $display_address .= ' ' . $address['state'];
        }

        if (isset($address['zip'])) {
            $display_address .= ' ' . $address['zip'] . "<br>";
        }

        $display_address .= "<br>";

        if (isset($address['country'])) {
            $display_address .= $address['country'];
        }

        return $display_address;
    }

    public function scopeMyInvoices($query)
    {
        return $query->where('user_id', user()->id);
    }

    public function markAsPaid()
    {
        InvoicePaidEvent::dispatch($this);

        $this->fill(['status' => 'paid'])->save();
    }

    public function markAsFailed()
    {
        $this->fill(['status' => 'failed'])->save();
    }

    public function markAsCancelled()
    {
        $this->fill(['status' => 'cancelled'])->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the owning invoicable models.
     */
    public function invoicable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    public function getPdfFileName($fullPath = false)
    {
        $fileName = "invoice_{$this->code}.pdf";

        if ($fullPath) {
            if (!\File::exists(storage_path('app/attachments/invoices/' . $this->id))) {
                \File::makeDirectory(storage_path('app/attachments/invoices/' . $this->id), 0755, true);
            }

            $fileName = storage_path('app/attachments/invoices/' . $this->id . '/' . $fileName);
        }

        return $fileName;
    }

    /**
     * @return string
     */
    public function getInvoicePaymentDetails()
    {
        $invoicable = $this->invoicable;

        $gatewayPaymentDetails = '';

        if ($invoicable && method_exists($invoicable, 'gateway')) {
            $gateway = $invoicable->gateway();

            if ($gateway) {
                $gatewayPaymentDetails = $gateway->getPaymentDetails($this);
            }
        }

        return $gatewayPaymentDetails;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
