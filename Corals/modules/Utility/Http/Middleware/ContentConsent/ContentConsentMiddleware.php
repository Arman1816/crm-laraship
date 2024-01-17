<?php

namespace Corals\Modules\Utility\Http\Middleware\ContentConsent;

use Closure;
use Corals\Settings\Facades\Settings;

class ContentConsentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (user()) {
            return $next($request);
        }

        $consentSettings = Settings::get('utility_content_consent_*');

        if (empty($consentSettings['utility_content_consent_enabled']) || !$consentSettings['utility_content_consent_enabled']) {
            return $next($request);
        }

        $consentState = $request->cookie('content_consent_state');

        if (('accepted' === $consentState
                || $request->is(['*content-consent-answer*']))
            || (!empty($consentState) && $request->is([
                    $consentSettings['utility_content_consent_rejected_redirect_url'],
                ]))
        ) {
            return $next($request);
        } else {
            //push assets here
            \Assets::add(asset('assets/modules/utility/content-consent/js/content-consent.js'));
            return $next($request);
        }
    }
}
