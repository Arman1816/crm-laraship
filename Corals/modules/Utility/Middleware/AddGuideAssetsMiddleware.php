<?php

namespace Corals\Modules\Utility\Middleware;

use Closure;
use Corals\Modules\Utility\Models\Guide\Guide;

class AddGuideAssetsMiddleware
{
    
    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!schemaHasTable('utility_guides')) {
            return $next($request);
        }

        $guideableUrl = $this->guideableUrl($request);

        if (!empty($guideableUrl)) {
            \Utility::includeGuidesAssets($guideableUrl->getProperty('guide_config'), $guideableUrl->url ?? $guideableUrl->route);
        }

        return $next($request);
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected function guideableUrl($request)
    {
        $url = $request->path();
        $route = $request->route()->uri;

        return Guide::query()->where(function ($query) use ($url, $route) {
            $query->where('url', $url)
                ->orWhere('route', $route);
        })->first();
    }
}
