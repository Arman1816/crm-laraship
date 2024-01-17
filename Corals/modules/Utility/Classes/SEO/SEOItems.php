<?php

namespace Corals\Modules\Utility\Classes\SEO;


class SEOItems
{

    public function getRouteManager()
    {
        $routes = [];

        foreach (\Route::getRoutes()->getIterator() as $route) {
            if (in_array('api', $route->action['middleware'])) {
                continue;
            }

            if (!in_array('GET', $route->methods)) {
                continue;
            }

            $routes[$route->uri] = $route->uri;
        }

        return $routes;
    }

}
