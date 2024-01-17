<?php

namespace Corals\Modules\Subscriptions\Classes;

use Corals\Modules\Subscriptions\Exceptions\LimitReachedException;
use Corals\Modules\Subscriptions\Facades\SubscriptionsManager as SubscriptionsManagerFacade;
use Corals\Modules\Subscriptions\Models\Feature;
use Corals\Modules\Subscriptions\Models\FeatureModel;
use Corals\Modules\Subscriptions\Models\PlanUsage;
use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Corals\Settings\Facades\Settings;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UsageManager
{
    public function getModelFeatures($model, $subscription = null)
    {
        if (is_null($subscription) && func_num_args() === 1) {
            $subscription = $this->getUserSubscription();
        }

        if (!$subscription) {
            return [];
        }

        $plan = $subscription->plan;

        if (is_object($model)) {
            $modelClass = get_class($model);
        } else {
            $modelClass = $model;
        }

        $planFeaturesIds = $plan->features()->pluck('features.id')->toArray();

        $modelFeaturesIds = FeatureModel::query()->where('model', $modelClass)
            ->whereIn('feature_id', $planFeaturesIds)->pluck('feature_id')
            ->toArray();

        return $plan->features()->whereIn('feature_id', $modelFeaturesIds)->get();
    }

    /**
     * @param $model
     * @param $usageDetails
     * @param null $product
     * @param null $plan
     * @param null $user
     * @return null
     * @throws \Exception
     */
    public function recordAUsage($model, $usageDetails, $product = null, $plan = null, $user = null)
    {
        $subscription = $this->getUserSubscription($product, $plan, $user);

        $features = $this->getModelFeatures($model, $subscription);

        foreach ($features as $feature) {
            $cycleId = null;
            $currentCycle = null;

            if ($feature->per_cycle) {
                $currentCycle = SubscriptionsManagerFacade::getCurrentCycle($subscription);

                $cycleId = $currentCycle->id;
            }

            $reachedLimit = $this->isFeatureUsageReachedLimit($feature, $subscription, $currentCycle);

            if ($reachedLimit) {
                throw new LimitReachedException($subscription, $feature, $currentCycle);
            }

            PlanUsage::query()->create([
                'subscription_id' => $subscription->id,
                'feature_id' => $feature->id,
                'cycle_id' => $cycleId,
                'usage_details' => $usageDetails,
            ]);
        }
    }

    public function getUserSubscription($product = null, $plan = null, $user = null)
    {
        if (is_null($user)) {
            $user = user()->getOwner();
        }

        $subscription = $user->currentSubscription($product, $plan);

        if (!$subscription) {
            return null;
        }

        return $subscription;
    }

    /**
     * @param $model
     * @return bool
     * @throws \Exception
     */
    public function isModelUsageReachedLimit($model)
    {
        $subscription = $this->getUserSubscription();

        $features = $this->getModelFeatures($model, $subscription);

        $reachedLimit = false;

        foreach ($features as $feature) {
            $currentCycle = null;

            if ($feature->per_cycle) {
                $currentCycle = SubscriptionsManagerFacade::getCurrentCycle($subscription);
            }

            $reachedLimit = $this->isFeatureUsageReachedLimit($feature, $subscription, $currentCycle);

            if ($reachedLimit) {
                $this->flashLimitReachedMessage($feature);
                break;
            }
        }

        return $reachedLimit;
    }

    /**
     * @param $feature
     */
    protected function flashLimitReachedMessage($feature): void
    {
        push_to_general_site_notifications(
            $feature->limit_reached_message,
            'warning',
            "feature_{$feature->id}"
        );
    }

    /**
     * @param $feature
     * @param null $subscription
     * @param null $currentCycle
     * @return bool
     * @throws \Exception
     */
    public function isFeatureUsageReachedLimit($feature, $subscription = null, $currentCycle = null)
    {
        $reachedLimit = false;


        [$subscription, $currentCycle] = $this->getSubscriptionAndCycle($feature, $subscription, $currentCycle);

        $planFeature = $subscription->plan->features()->where('feature_id', $feature->id)->first();

        $usageQuery = $this->getFeatureUsageQuery($feature, $subscription, $currentCycle);

        switch ($planFeature->type) {
            case 'quantity':
                $usageCount = $usageQuery->count();

                if ($planFeature->pivot->value == $usageCount) {
                    $reachedLimit = true;
                }
                break;
            case 'boolean':
                $reachedLimit = $usageQuery->exists();
                break;
        }

        return $reachedLimit;
    }

    /**
     * @param $subscription
     * @param $currentCycle
     * @param $feature
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getFeatureUsageQuery($feature, $subscription, $currentCycle)
    {
        return PlanUsage::query()->where([
            ['subscription_id', '=', $subscription->id],
            ['cycle_id', '=', optional($currentCycle)->id],
            ['feature_id', '=', $feature->id],
        ]);
    }

    /**
     * @param $feature
     * @param null $subscription
     * @param null $currentCycle
     * @return array
     * @throws \Exception
     */
    protected function getSubscriptionAndCycle($feature, $subscription = null, $currentCycle = null): array
    {
        if (is_null($subscription)) {
            $subscription = $this->getUserSubscription();
        }

        if ($feature->per_cycle && is_null($currentCycle) && func_num_args() != 3) {
            $currentCycle = SubscriptionsManagerFacade::getCurrentCycle($subscription);
        }

        return [
            $subscription,
            $currentCycle
        ];
    }

    /**
     * @param Feature $feature
     * @param null $subscription
     * @param null $currentCycle
     * @return mixed
     * @throws \Exception
     */
    public function getFeatureUsageStatistics(Feature $feature, $subscription = null, $currentCycle = null)
    {
        [$subscription, $currentCycle] = $this->getSubscriptionAndCycle($feature, $subscription, $currentCycle);

        $featureUsageCount = $this->getFeatureUsageQuery($feature, $subscription, $currentCycle)->count();

        $planFeature = $subscription->plan->features()->where('feature_id', $feature->id)->first();


        if (method_exists($this, $method = sprintf("get%sFeatureStatistics", Str::studly($planFeature->type)))) {
            return $this->{$method}($feature, $featureUsageCount, $planFeature->pivot->value);
        }

        return [];
    }

    /**
     * @param $featureUsageCount
     * @param $featureLimit
     * @return array
     */
    protected function getQuantityFeatureStatistics($feature, $featureUsageCount, $featureLimit)
    {
        if ($featureLimit) {
            $usedPercentage = ($featureUsageCount / $featureLimit) * 100;
        }

        return [
            'feature_name' => $feature->name,
            'feature_caption' => $feature->caption,
            'feature_usage_count' => $featureUsageCount,
            'feature_limit' => $featureLimit,
            'used_percentage' => $usedPercentage ?? 0
        ];
    }

    /**
     * @param $model
     * @param null $model_id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPlanUsageByModel($model, $model_id = null)
    {
        if (is_object($model)) {
            $modelClass = get_class($model);
        } else {
            $modelClass = $model;
        }

        $modelClass = addcslashes($modelClass, '\\');

        $modelTypeQueryString = sprintf("JSON_EXTRACT(usage_details, '$.model_type') = '%s'", $modelClass);

        $modelPlanUsage = PlanUsage::query()->whereRaw($modelTypeQueryString);

        if (!is_null($model_id)) {
            $modelIdQueryString = sprintf("JSON_EXTRACT(usage_details, '$.model_id') = %s", $model_id);

            $modelPlanUsage->whereRaw($modelIdQueryString);
        }

        return $modelPlanUsage->get();
    }


    /**
     * @param $feature
     * @param null $source
     * @param null $code
     * @return array
     */
    public function getSourceValues($feature, $source = null, $code = null)
    {
        if ($feature) {
            $extras = $feature->extras['config'] ?? [];
            $source = Arr::get($extras, 'source');
            $code = Arr::get($extras, 'code');
        }

        switch ($source) {
            case 'settings':
                return Settings::get($code, []);
            case 'list_of_values':
                return ListOfValue::query()->join('utility_list_of_values as children', 'utility_list_of_values.id',
                    'children.parent_id')
                    ->where('utility_list_of_values.code', $code)->pluck('children.value', 'children.id')->toArray();
            case 'config':
                return get_array_key_translation(config($code));
            default:
                return [];
        }
    }

    /**
     * @param $source
     * @param $code
     * @return array
     */
    public function getPlanFeatureConfig($source, $code): array
    {
        if (!($currentSubscription = user()->currentSubscription)) {
            return [];
        }


        $feature = $currentSubscription->plan->features()->whereRaw(
            "json_search(json_keys(extras),'one','config') is not null 
            AND 
        json_search(json_extract(extras,'$.config.source'),'one', ?) is not null
            AND
       json_search(json_extract(extras,'$.config.code'),'one', ?) is not null", [$source, $code]
        )->first();

        $sourceValues = $this->getSourceValues(null, $source, $code);

        if (!$feature) {
            return $sourceValues;
        }

        $featureKeys = explode(',', $feature->pivot->value);

        if (empty($featureKeys)) {
            return [];
        }

        return Arr::only($sourceValues, $featureKeys);
    }
}
