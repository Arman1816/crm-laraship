<?php

namespace Corals\Modules\Payment\Common\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelUniqueCode;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class InvoiceItem extends BaseModel
{
    use LogsActivity, ModelUniqueCode, PresentableTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'payment_common.models.invoice_item';

    protected static $logAttributes = [];

    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get all of the owning itemable models.
     */
    public function itemable()
    {
        return $this->morphTo();
    }
}
