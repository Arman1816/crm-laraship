<?php

namespace Corals\Modules\Marketplace\Models;

use Carbon\Carbon;
use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\User\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;

class Coupon extends BaseModel
{
    use PresentableTrait, LogsActivity;

    protected $table = 'marketplace_coupons';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.coupon';

    protected static $logAttributes = [];

    protected $guarded = ['id'];

    protected $casts = [
    ];

    protected $dates = [
        'start',
        'expiry'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'marketplace_coupon_user');
    }

    public function status()
    {
        if ($this->start <= Carbon::today()->toDateString() && ($this->expiry >= Carbon::today()->toDateString())) {
            return "active";

        } else if ($this->start > Carbon::today()->toDateString()) {
            return "pending";

        } else {
            return "expired";
        }
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'marketplace_coupon_product');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
