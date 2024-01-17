<?php

namespace Corals\Modules\Subscriptions\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class SubscriptionCycle extends BaseModel
{
    use PresentableTrait, LogsActivity;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'subscriptions.models.subscription_cycle';

    protected $table = 'subscription_cycles';

    protected static $logAttributes = ['name', 'subscription_id', 'starts_at', 'ends_at'];

    protected $guarded = ['id'];

    protected $dates = ['starts_at', 'ends_at'];

    public function usages()
    {
        return $this->hasMany(PlanUsage::class);
    }

    public function getPeriodAttribute()
    {
        return sprintf("[%s - %s]", format_date_time($this->starts_at), format_date_time($this->ends_at));
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
