<?php

namespace Corals\Modules\Subscriptions\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Foundation\Traits\GatewayStatusTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Plan extends BaseModel
{
    use PresentableTrait, LogsActivity, GatewayStatusTrait;

    protected $propertiesColumn = 'extras';

    protected $casts = [
        'free_plan' => 'boolean',
        'is_visible' => 'boolean',
        'extras' => 'json'
    ];

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'subscriptions.models.plan';

    protected static $logAttributes = [
        'name', 'description', 'price', 'discount', 'recommended', 'trial_period', 'free_plan', 'display_order', 'status'
    ];

    protected $guarded = ['id'];

    protected $appends = ['currency'];

    public function getCurrencyAttribute()
    {
        $currency = \Payments::admin_currency_code();

        return $currency;
    }

    public function getCurrencyIconAttribute()
    {
        $currency = $this->currency;

        return 'fa fa-fw fa-' . strtolower($currency);
    }

    public function getCycleCaptionAttribute()
    {
        $caption = '';

        if ($this->attributes['bill_frequency'] > 1) {
            $caption = 'Every ' . $this->attributes['bill_frequency'] . ' ' . ucfirst(\Str::plural($this->attributes['bill_cycle'], $this->attributes['bill_frequency']));
        } else {
            switch ($this->attributes['bill_cycle']) {
                case 'week':
                    $caption = 'Weekly';
                    break;
                case 'month':
                    $caption = 'Monthly';
                    break;
                case 'year':
                    $caption = 'Yearly';
                    break;
            }
        }

        return $caption;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class)
            ->withPivot('value', 'plan_caption')->orderBy('display_order');
    }

    public function activeFeatures()
    {
        return $this->features()->where('features.status', 'active');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }



}
