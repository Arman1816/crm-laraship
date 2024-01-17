<?php

namespace Corals\Modules\Subscriptions\Middleware;

use Closure;
use Corals\Modules\Subscriptions\Models\Feature;

class SubscriptionMiddleware
{
    /**
     * @var
     */
    protected $owner;

    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!user()) {
            return $next($request);
        }

        $this->owner = user()->getOwner();

        if ($this->owner->hasPermissionTo('Administrations::admin.subscription')) {
            return $next($request);
        }

        if ($this->shouldSubscribe()) {
            return redirect('subscriptions/select');
        }

        return $next($request);
    }

    /**
     * @return bool
     */
    protected function shouldSubscribe(): bool
    {
        //url is not required subscription to be accessed return false
        if ($this->isUrlNotSubscriptionLimited()) {
            return false;
        }

        //subscription not required no return false
        if (!$this->owner->subscriptionRequired()) {
            return false;
        }

        //user not subscribed should subscribe return true;
        if ($this->owner->notSubscribed()) {
            return true;
        }

        //user not subscribed to url return true;
        if ($this->isUserNotSubscribedToUrl()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isUrlNotSubscriptionLimited(): bool
    {
        try {
            $limitedUrls = Feature::query()->pluck('related_urls')->flatten()->toArray();
        } catch (\Exception $exception) {
            report($exception);
            $limitedUrls = [];
        }

        return !request()->is(array_filter($limitedUrls));
    }

    /**
     * @return bool
     */
    protected function isUserNotSubscribedToUrl(): bool
    {
        $activeSubscriptions = $this->owner->activeSubscriptions;

        $notSubscribed = true;

        foreach ($activeSubscriptions as $subscription) {
            $limitedUrls = $subscription->plan
                ->features()
                ->pluck('related_urls')
                ->flatten()
                ->toArray();

            if (request()->is(array_filter($limitedUrls))) {
                $notSubscribed = false;
                break;
            }
        }

        return $notSubscribed;
    }

}
