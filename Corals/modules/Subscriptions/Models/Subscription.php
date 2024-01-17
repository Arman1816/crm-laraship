<?php

namespace Corals\Modules\Subscriptions\Models;

use Carbon\Carbon;
use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\User\Models\User;

class Subscription extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    use PresentableTrait;


    protected $guarded = ['id'];

    public $config = 'subscriptions.models.subscription';

    protected $propertiesColumn = 'extras';
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at',
        'ends_at',
        'next_billing_at'
    ];

    protected $casts = [
        'extras' => 'json',
        'properties' => 'json',
        'shipping_address' => 'array',
        'billing_address' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Determine if the subscription is active, on trial, or within its grace period.
     *
     * @return bool
     */
    public function valid()
    {
        return ($this->pending() || $this->active());
    }

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function active()
    {
        return (is_null($this->ends_at) || $this->onGracePeriod()) &&
            (is_null($this->status) || in_array($this->status, ["active", "canceled"]));
    }

    /**
     * Determine if the subscription is pending.
     *
     * @return bool
     */
    public function pending()
    {
        return $this->status == "pending";
    }


    /**
     * Determine if the subscription is no longer active.
     *
     * @return bool
     */
    public function cancelled()
    {
        return !is_null($this->ends_at);
    }

    /**
     * Mark the subscription as cancelled.
     *
     * @return void
     */
    public function markAsCancelled()
    {
        try {
            $end_date = Carbon::now();
            $end_date = \Filters::do_filter('subscription_cancellation_end_date', $end_date, $this);

            \Actions::do_action('pre_subscription_marked_as_cancelled', $this);
            $this->fill(['ends_at' => $end_date, 'status' => 'canceled'])->save();
        } catch (\Exception $exception) {
            flash("Error Cancelling ")->warning();
            log_exception($exception, 'SubscriptionController', 'cancel');
        }
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->fill(['status' => $status])->save();
    }

    /**
     * Determine if the subscription is within its trial period.
     *
     * @return bool
     */
    public function onTrial()
    {
        if (!is_null($this->trial_ends_at)) {
            return Carbon::today()->lt($this->trial_ends_at);
        } else {
            return false;
        }
    }

    /**
     * Determine if the subscription is within its grace period after cancellation.
     *
     * @return bool
     */
    public function onGracePeriod()
    {
        if (!is_null($endsAt = $this->ends_at)) {
            return Carbon::now()->lt(Carbon::instance($endsAt));
        } else {
            return false;
        }
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoicable')->latest();
    }

    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoicable')->latest();
    }

    public function getInvoiceReference($target = "dashboard")
    {
        $plan = $this->plan;

        $product = $this->plan->product;

        $invRef = "{$product->name} -  {$plan->name} [$this->subscription_reference]";

        if ($target == "pdf") {
            return $invRef;
        } else {
            return "<a href='" . url('subscriptions/products/' . $product->hashed_id) . "'>$invRef</a>";
        }
    }

    public function remainingDays()
    {
        $stated_at = $this->created_at;

        $next_billing_at = $this->next_billing_at;

        $ends_at = $this->ends_at;

        $remainingDays = 0;

        if ($ends_at) {
            $total_subscriptions_days = $ends_at->diffInDays($stated_at);

            $passed_subscription_days = now()->diffInDays($stated_at);

            $remainingDays = $total_subscriptions_days - $passed_subscription_days;
        } elseif ($next_billing_at) {
            $remainingDays = $next_billing_at->diffInDays();
        } else {
            if ($next_cycle_date = $this->getNextCycleDate($stated_at)) {
                $remainingDays = $next_cycle_date->diffInDays();
            }
        }

        return $remainingDays;
    }

    public function getNextCycleDate(Carbon $starts_at = null)
    {
        $cycleStarts_at = $starts_at ? $starts_at->copy() : $this->created_at;

        $next_cycle_date = null;

        $plan_cycle = $this->plan->bill_cycle;

        $plan_freq = $this->plan->bill_frequency;

        switch ($plan_cycle) {
            case 'week':
                $next_cycle_date = $cycleStarts_at->addWeeks($plan_freq);

                while (now()->gt($next_cycle_date)) {
                    $next_cycle_date = $cycleStarts_at->addWeeks($plan_freq);
                }
                break;
            case 'month':
                $next_cycle_date = $cycleStarts_at->addMonths($plan_freq);

                while (now()->gt($next_cycle_date)) {
                    $next_cycle_date = $cycleStarts_at->addMonths($plan_freq);
                }
                break;
            case 'year':
                $next_cycle_date = $cycleStarts_at->addYears($plan_freq);

                while (now()->gt($next_cycle_date)) {
                    $next_cycle_date = $cycleStarts_at->addYears($plan_freq);
                }
                break;
        }

        return $next_cycle_date;
    }

    public function planUsages()
    {
        return $this->hasMany(PlanUsage::class);
    }

    public function cycles()
    {
        return $this->hasMany(SubscriptionCycle::class)->latest();
    }

    public function currentCycle()
    {
        return $this->cycles()->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())->first();
    }

    public function lastCycle()
    {
        return $this->cycles()->where('ends_at', '<', now())->latest('ends_at')->first();
    }

    public function gateway()
    {
        $gateway_name = $this->getAttribute('gateway');

        if (!$gateway_name) {
            return null;
        }

        try {
            $subscription = new \Corals\Modules\Subscriptions\Classes\Subscription($gateway_name);

            return $subscription->gateway;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
