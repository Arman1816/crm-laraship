<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\GatewayStatusTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\CMS\Models\Content;
use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\User\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends BaseModel
{
    use PresentableTrait, LogsActivity, GatewayStatusTrait;

    protected $table = 'marketplace_orders';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.order';

    protected static $logAttributes = ['status', 'amount'];

    protected $guarded = ['id'];

    protected $casts = [
        'shipping' => 'array',
        'billing' => 'array',
        'properties' => 'json'
    ];

    public function scopeMyOrders($query)
    {
        return $query->where('user_id', user()->id);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoicable');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getInvoiceReference($target = "dashboard")
    {
        $order_number = $this->order_number;
        if ($target == "pdf") {
            return $order_number;
        } else {
            return "<a href='" . url('marketplace/orders/' . $this->hashed_id) . "'>  $order_number </a>";
        }
    }

    /**
     * Get all of the premuim posts for the order.
     */
    public function posts()
    {
        return $this->morphToMany(Content::class, 'sourcable', 'postables');
    }

    /**
     * Get all of the transactions  for the order.
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'sourcable');
    }

    public function payouts()
    {
        return $this->transactions()
            ->where('type', 'payout')
            ->where('status', '<>', 'cancelled');
    }

    public function commissions()
    {
        return $this->transactions()
            ->where('type', 'commission')
            ->where('status', '<>', 'cancelled');
    }


    public function getTransactionSource()
    {
        $order_number = $this->order_number;
        return "<a target='_blank' href='" . url('marketplace/orders/' . $this->hashed_id) . "'>  $order_number </a>";
    }

    /**
     * Get all of the transactions  for the order.
     */
    public function generator()
    {
        return $this->belongsToThrough(User::class, Store::class, 'id', '', [Store::class => 'store_id']);
    }

    public function getPaymentRefundedAmount()
    {
        $refunded_amount = $this->transactions()
            ->where('type', 'order_refund')
            ->sum('amount');

        return $refunded_amount * -1;
    }


}
