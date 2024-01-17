<?php

namespace Corals\Modules\Subscriptions\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class PlanUsage extends BaseModel
{
    use PresentableTrait, LogsActivity;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'subscriptions.models.plan_usage';

    protected $table = 'plan_usage';

    protected static $logAttributes = ['subscription_id', 'feature_id', 'cycle_id', 'properties', 'usage_details'];

    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json',
        'usage_details' => 'json'
    ];

    public function cycle()
    {
        return $this->belongsTo(SubscriptionCycle::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
