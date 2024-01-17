<?php

namespace Corals\Modules\Subscriptions\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Subscriptions\Classes\Subscription;
use Corals\Modules\Subscriptions\Http\Requests\API\SubscriptionCheckoutRequest;
use Corals\Modules\Subscriptions\Models\Plan;
use Corals\Modules\Subscriptions\Models\Product;
use Corals\Modules\Subscriptions\Services\SubscriptionService;
use Corals\Modules\Subscriptions\Transformers\API\ProductPresenter;
use Corals\Modules\Subscriptions\Transformers\API\SubscriptionPresenter;
use Illuminate\Http\Request;

class SubscriptionsController extends APIBaseController
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['pricingPublic']);

        parent::__construct();
    }

    public function pricingPublic(Request $request, Product $product)
    {
        return $this->pricing($request, $product);
    }

    public function pricing(Request $request, Product $product)
    {
        try {
            $products = $this->subscriptionService->getPricingProducts($request, $product);

            $products = (new ProductPresenter())->present($products)['data'];

            return apiResponse($products);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param SubscriptionCheckoutRequest $request
     * @param Plan $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(SubscriptionCheckoutRequest $request, Plan $plan)
    {
        try {
            $user = user();

            $user_data = $request->only('card_last_four', 'card_brand', 'integration_id', 'gateway');

            if (!empty($user_data)) {
                //avoid clear current data
                $user_data = array_filter($user_data, function ($element) {
                    return !empty($element);
                });

                $user->update($user_data);
            }

            if ($request->input('save_billing')) {
                $user->saveAddress($request->input('billing_address'), 'billing');
            }

            if ($request->input('save_shipping')) {
                $user->saveAddress($request->input('shipping_address'), 'shipping');
            }

            $gateway = $request->get('gateway');

            $subscription = null;

            if ($gateway) {
                $subscription = new Subscription($gateway);
            }

            $subscription_data = $request->only('billing_address', 'shipping_address');

            $user_subscription = $this->subscriptionService->subscribe($plan, $subscription, $user, $subscription_data);

            $subscription_message = $this->subscriptionService->getSubscriptionCreationMessage($user_subscription);

            $user_subscription->setPresenter(new SubscriptionPresenter());

            return apiResponse($user_subscription->presenter(), $subscription_message);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function cancelPlanSubscription(Request $request, Plan $plan)
    {
        try {
            $message = $this->subscriptionService->cancelPlanSubscription($request, $plan);

            return apiResponse([], $message);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
