<?php

namespace Corals\Modules\Subscriptions\Widgets;

use Corals\Modules\Subscriptions\Charts\SubscriptionRatio;
use Corals\Modules\Subscriptions\Models\Plan;

class SubscriptionRatioWidget
{

    function __construct()
    {
    }

    function run($args)
    {


        $data = Plan::withCount(['subscriptions' => function ($q) {
            $q->where('status', 'active');
        }])->having('subscriptions_count', '>', 0)->get()->pluck('subscriptions_count', 'name')->toArray();


        $chart = new SubscriptionRatio();
        $chart->labels(array_keys($data));
        $chart->dataset(trans('Subscriptions::labels.widget.subscription'), 'pie', array_values($data));

        $chart->options([
            'plugins' => '{
                    colorschemes: {
                        scheme: \'brewer.Paired12\'
                    }
                }'
        ]);


        return view('Corals::chart')->with(['chart' => $chart])->render();


    }

}
