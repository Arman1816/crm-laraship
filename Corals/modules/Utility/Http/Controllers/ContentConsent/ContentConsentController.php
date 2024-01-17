<?php

namespace Corals\Modules\Utility\Http\Controllers\ContentConsent;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Settings\Facades\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class ContentConsentController extends BaseController
{
    /**
     * ContentConsentController constructor.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __construct()
    {
        $this->resource_url = url('utilities/content-consent-settings');

        $this->title = 'Utility::module.content_consent.title';
        $this->title_singular = 'Utility::module.content_consent.title_singular';

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['setContentConsentAnswer', 'modal']);

        parent::__construct();
    }

    private function canAccess()
    {
        if (user()->cannot('Utility::content_consent.manage')) {
            abort(403);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->canAccess();

        $settings = config('utility.content_consent_settings');

        return view('Utility::content_consent.settings')->with(compact('settings'));
    }

    public function setSettings(Request $request)
    {
        $this->canAccess();

        $settings = config('utility.content_consent_settings');

        $validationRules = array_combine(array_keys($settings), Arr::pluck($settings, 'validation'));

        $attributes = [];

        foreach (array_keys($settings) as $key) {
            $attributes[$key] = 'setting';
        }

        $this->validate($request, $validationRules, [], $attributes);

        try {
            foreach ($settings as $key => $setting) {
                $value = $request->get($key);

                Settings::set($key, $value, 'Utilities', $setting['settings_type']);
            }

            flash(trans('Corals::messages.success.saved', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, 'ContentConsentController', 'setSettings');
        }

        return redirectTo($this->resource_url);
    }

    public function setContentConsentAnswer(Request $request, $state)
    {
        if (!in_array($state, ['accepted', 'rejected'])) {
            abort(404);
        }

        $consentSettings = Settings::get('utility_content_consent_*');

        $askEvery = $consentSettings['utility_content_consent_ask_every'];

        $askEveryMinutes = $askEvery * 1440;

        Cookie::queue(Cookie::make('content_consent_state', $state, $askEveryMinutes));

        if ('accepted' === $state) {
            return redirect()->back();
        } else {
            $redirectURL = $consentSettings['utility_content_consent_rejected_redirect_url'];
            return redirect($redirectURL);
        }
    }

    public function modal(Request $request)
    {
        return view('Utility::content_consent.modal');
    }
}
