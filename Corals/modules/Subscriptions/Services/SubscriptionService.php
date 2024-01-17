<?php

namespace Corals\Modules\Subscriptions\Services;

use Corals\Modules\Subscriptions\Classes\Subscription as SubscriptionClass;
use Corals\Modules\Subscriptions\Facades\SubscriptionsManager;
use Corals\Modules\Subscriptions\Models\Plan;
use Corals\Modules\Subscriptions\Models\Product;
use Corals\User\Models\User;
use Illuminate\Http\Request;

class SubscriptionService
{
    /**
     * @param $request
     * @param null $product
     * @return mixed
     */
    public function getPricingProducts($request, $product = null)
    {
        $products = Product::active();

        if ($product && $product->exists) {
            $products = $products->where('id', $product->id);
        }

        $products = $products->get();

        return $products;
    }

    /**
     * @param Plan $plan
     * @param SubscriptionClass|null $subscriptionClass
     * @param User|null $user
     * @param array $subscription_data
     * @return bool|null
     * @throws \Exception
     */
    public function subscribe(
        Plan $plan,
        SubscriptionClass $subscriptionClass = null,
        User $user = null,
        $subscription_data = []
    ) {
        if (is_null($user)) {
            $user = user();
        }

        $user_subscription = null;

        if ($user->subscribed(null, $plan->id)) {
            //already subscribed on the requested plan
            throw new \Exception(trans('Subscriptions::exception.subscription.already_subscribe',
                ['name' => $plan->name]));
        } elseif ($user->subscribed($plan->product_id)) {
            $userProductSubscription = $user->currentSubscription($plan->product_id);

            if (!\Payments::isGatewaySupported($userProductSubscription->gateway)) {
                /**
                 * new subscription on new product
                 * in case current product plan is free;
                 **/
                if (!$subscriptionClass) {
                    throw new \Exception('Invalid Subscription Object');
                }
                $user_subscription = $subscriptionClass->createSubscription($plan);
                $userProductSubscription->markAsCancelled();
            } else {
                $current_product_subscription = new SubscriptionClass($userProductSubscription->gateway);

                if ($plan->free_plan) {
                    /**
                     * Swap to free plan
                     * cancel subscription in case the new plan is free plan
                     */
                    $current_product_subscription->cancelSubscription($userProductSubscription->plan);
                    //subscribe to free plan
                    $user_subscription = $user->subscriptions()->create([
                        'plan_id' => $plan->id,
                        'gateway' => 'Free',
                        'subscription_reference' => 'free_' . \Str::random(6),
                    ]);

                    SubscriptionsManager::generateCycle($user_subscription);

                    \Actions::do_action('post_create_subscription', $user_subscription);
                } else {
                    if (!$subscriptionClass) {
                        throw new \Exception('Invalid Subscription Object');
                    }

                    //cancel current subscription if different gateway
                    if ($current_product_subscription->gateway->getName() != $subscriptionClass->gateway->getName()) {
                        $current_product_subscription->cancelSubscription($userProductSubscription->plan);
                        $user_subscription = $subscriptionClass->createSubscription($plan, $subscription_data);
                    } else {
                        // swap to another plan on same product
                        $user_subscription = $subscriptionClass->swapSubscription($plan);
                    }

                    // no invoice items generated on trial period
                    if (!$userProductSubscription->onTrial() && $subscriptionClass->gateway->getConfig('require_invoice_creation')) {
                        $invoiceReference = $subscriptionClass->createInvoice($user, $plan);
                        if ($invoiceReference) {
                            $subscriptionClass->payInvoice($invoiceReference);
                        }
                    }
                }
            }
        } else {
            // new subscription on new product
            if ($plan->free_plan) {
                $user_subscription = $user->subscriptions()->create([
                    'plan_id' => $plan->id,
                    'gateway' => 'Free',
                    'subscription_reference' => 'free_' . \Str::random(6),
                ]);

                SubscriptionsManager::generateCycle($user_subscription);
            } elseif (!empty($subscriptionClass)) {
                $user_subscription = $subscriptionClass->createSubscription($plan);
            } else {
                throw new \Exception('Invalid Subscription Object');
            }
        }

        if (!empty($subscription_data)) {
            $user_subscription->update($subscription_data);
        }

        return $user_subscription;
    }

    public function getSubscriptionCreationMessage($user_subscription)
    {
        if ($user_subscription->status == "pending") {
            $message = trans('Subscriptions::labels.subscription.subscription_request_received');
        } else {
            $message = trans('Subscriptions::labels.subscription.you_have_subscribed_success',
                ['name' => $user_subscription->plan->name]);
        }

        $message = \Filters::do_filter('subscription_creation_message', $message, $user_subscription);

        return $message;
    }

    /**
     * @param Request $request
     * @param Plan $plan
     * @param User|null $user
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     * @throws \Exception
     */
    public function cancelPlanSubscription(Request $request, Plan $plan, User $user = null)
    {
        if (is_null($user)) {
            $user = user();
        }

        $current_subscription = $user->currentSubscription(null, $plan->id);

        if (!$user->subscribed(null, $plan->id)) {
            throw new \Exception(trans('Subscriptions::exception.subscription.invalid_cancellation_request'));
        } else {
            if (!\Payments::isGatewaySupported($current_subscription->gateway)) {
                // cancel subscription
                $current_subscription->markAsCancelled();
            } else {
                $subscriptionClass = new SubscriptionClass($current_subscription->gateway);
                $subscriptionClass->cancelSubscription($plan);
            }
        }

        $message = trans('Subscriptions::labels.subscription.subscription_cancelled_success', ['name' => $plan->name]);

        if (!$plan->free_plan) {
            $message .= trans('Subscriptions::labels.subscription.access_account_till_end');
        }

        return $message;
    }

    public function renewSubscription($subscription)
    {
        try {
            $subscriptionClass = new SubscriptionClass($subscription->gateway);

            $renewalResponse = $subscriptionClass->renewSubscription($subscription);

            event('notifications.subscription.subscription_renewal_invoice',
                ['subscription' => $subscription]);
        } catch (\Exception $exception) {
            //TODO:: Handle renewal exception
        }
    }

    public function expireSubscription($subscription)
    {
        $subscription->markAsCancelled();
    }
}
