<?php

namespace Corals\Modules\CMS\Widgets;

use Analytics;
use Corals\Modules\CMS\Charts\PageViews;
use Spatie\Analytics\Period;

class PageViewsWidget
{

    function __construct()
    {
    }

    function run($args)
    {
        try {
            $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days(30));
            $visitors = [];
            $pageviews = [];
            $totalViews = ['labels' => []];

            foreach ($analyticsData as $k => $item) {
                array_push($totalViews['labels'], $item['date']->format('d M'));

                array_push($visitors, $item['visitors']);
                array_push($pageviews, $item['pageViews']);
            }


            $chart = new PageViews();
            $chart->labels($totalViews['labels']);
            $chart->dataset('PageViews', 'line', $pageviews);
            $chart->dataset('Visitors', 'line', $visitors);


            return view('Corals::chart')->with(['chart' => $chart])->render();


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
