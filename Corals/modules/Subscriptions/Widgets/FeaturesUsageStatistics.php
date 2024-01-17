<?php

namespace Corals\Modules\Subscriptions\Widgets;

use Corals\Modules\Subscriptions\Models\Feature;

class FeaturesUsageStatistics
{

    function __construct()
    {
    }

    function run($args)
    {
        $activeSubscriptions = user()->getOwner()->activeSubscriptions;

        $widgets = '';

        foreach ($activeSubscriptions as $subscription) {
            $subscription->plan
                ->features()
                ->where('is_visible', true)
                ->whereIn('type', config('subscriptions.features_has_widgets_types'))
                ->chunk(100, function ($features) use (&$widgets, $subscription) {

                    $features->chunk(3)->each(function ($featuresChunk) use (&$widgets, $subscription) {
                        $widgets .= "<div class='row mb-3'>";

                        $featuresChunk->each(function (Feature $feature) use (&$widgets, $subscription) {
                            $view = "Subscriptions::features.features_usages_statistics_types.$feature->type";

                            if (view()->exists($view)) {
                                $widgets .= view($view)->with([
                                    'data' => \UsageManager::getFeatureUsageStatistics($feature, $subscription)
                                ])->render();
                            }
                        });

                        $widgets .= "</div>";

                    });


                });

        }

        return $widgets;
    }

}
