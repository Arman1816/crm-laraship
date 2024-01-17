<?php

namespace Corals\Modules\Marketplace\Hooks;


use Corals\Modules\Advert\DataTables\Scopes\AdvertiserOwnerBannersScope;
use Corals\Modules\Advert\DataTables\Scopes\AdvertiserOwnerCampaignsScope;
use Corals\Modules\Advert\DataTables\Scopes\AdvertiserOwnerImpressionsScope;
use Corals\Modules\Marketplace\DataTables\Scopes\StoreZonesScope;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\Modules\Subscriptions\Classes\Subscription as SubscriptionsClass;
use Corals\Modules\Subscriptions\Models\Plan;
use Corals\Modules\Subscriptions\Models\Subscription;

class Marketplace
{
    /**
     * Subscription constructor.
     */
    function __construct()
    {
    }


    public function show_cart_icon()
    {
        if (user()->hasPermissionTo('Marketplace::cart.access')) {
            echo '<li class="cart cart-menu" >
                        <a href = "' . url('marketplace/cart') . '" style = "padding: 10px 15px;" >
                            <i class="fa fa-2x fa-shopping-cart" style = "" ></i >
                            <span class="label label-success cart_total_label"
                                  id = "cart-header-total" >' . \ShoppingCart::totalAllInstances() . '</span >
                        </a >
                    </li >';
        }
    }

    /**
     * @param $user
     * @param $active_tab
     * @throws \Throwable
     */
    public function show_profile_tabs_items($user, $active_tab)
    {
        if ($user->hasPermissionTo('Marketplace::store.update')) {
            $profile_marketplace_tab_items = view('Marketplace::shop.partials.tabs_items')->with(compact('user', 'active_tab'))->render();
            echo $profile_marketplace_tab_items;
        }
    }

    public function show_profile_tabs_content($user, $active_tab)
    {


    }

    /**
     * @param $dashboard_content
     * @return string
     * @throws \Throwable
     */
    public function dashboard_content($dashboard_content, $active_tab)
    {
        if (user()->hasRole('superuser')) {
            $dashboard_content .= view('Marketplace::dashboard.superuser')->with(compact('active_tab'))->render();
        } else {
            $dashboard_content .= view('Marketplace::dashboard.user')->with(compact('active_tab'))->render();

        }


        return $dashboard_content;
    }

    public function show_store_selector()
    {
        Store::showStoreSelection();
    }

    public function create_store(Subscription $subscription)
    {
        $marketplace_subscription_product = \Settings::get('marketplace_general_subscription_product', '');
        if ($marketplace_subscription_product) {
            if (($subscription->active()) && ($subscription->plan->product->id == $marketplace_subscription_product)) {
                \Store::createStore($subscription->user, $subscription);
            }
        }
    }

    public function set_fallback_plan(Subscription $subscription)
    {

        $marketplace_subscription_product = \Settings::get('marketplace_general_subscription_product', '');
        $marketplace_fallback_plan = \Settings::get('marketplace_general_fallback_plan', '');
        if ($subscription->plan->product->id == $marketplace_subscription_product) {
            if ($marketplace_fallback_plan && ($marketplace_fallback_plan != $subscription->plan->id)) {
                try {
                    $user = $subscription->user;
                    $plan = Plan::findOrFail($marketplace_fallback_plan);
                    if ($plan->free_plan) {
                        $user->subscriptions()->create([
                            'plan_id' => $plan->id,
                            'gateway' => 'Free',
                            'subscription_reference' => 'free_' . \Str::random(6),
                        ]);
                    } else {
                        $subscription = new SubscriptionsClass($user->gateway);
                        $subscription->createSubscription($plan, $user);
                    }

                } catch (\Exception $exception) {
                    log_exception($exception);
                }
            }
        }
    }


    public function notify_witdrawal_request_transaction_update(Transaction $transaction)
    {

        if ($transaction->type == "withdrawal") {
            event('notifications.marketplace.withdrawal_request.updated', ['transaction' => $transaction]);

        }

    }

    public function add_store_campaigns_query($scopes, $class)
    {
        if (!\Store::isStoreAdmin()) {

            if ($store = \Store::getVendorStore()) {
                $scopes[] = new AdvertiserOwnerCampaignsScope($store);
            }
        }
        return $scopes;
    }

    public function add_store_zones_query($scopes, $class)
    {
        if (!\Store::isStoreAdmin()) {

            if ($store = \Store::getVendorStore()) {
                $scopes[] = new StoreZonesScope();
            }
        }
        return $scopes;
    }

    public function add_store_banners_query($scopes, $class)
    {
        if (!\Store::isStoreAdmin()) {

            if ($store = \Store::getVendorStore()) {
                $scopes[] = new AdvertiserOwnerBannersScope($store);
            }
        }
        return $scopes;
    }


    public function add_current_store_banners_query($scopes, $class)
    {

        if ($store = \Store::getStore()) {
            $scopes[] = new AdvertiserOwnerBannersScope($store);
        }

        return $scopes;
    }


    public function add_store_impressions_query($scopes, $class)
    {

        if (!\Store::isStoreAdmin()) {

            if ($store = \Store::getVendorStore()) {
                $scopes[] = new AdvertiserOwnerImpressionsScope($store);
            }
        }
        return $scopes;
    }

    public function set_advertiser_id($data)
    {
        if (!\Store::isStoreAdmin()) {

            if ($store = \Store::getVendorStore()) {
                if ($store->advertiser) {
                    $data['advertiser_id'] = $store->advertiser->id;

                }
            }
        }

        return $data;
    }


}

