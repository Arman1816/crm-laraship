<?php

namespace Corals\Modules\Subscriptions\Hooks;

use Corals\Modules\Payment\Common\DataTables\MyInvoicesDataTable;
use Corals\Modules\Subscriptions\Classes\Subscription as SubscriptionsClass;
use Corals\Modules\Subscriptions\DataTables\MySubscriptionCyclesDataTable;
use Corals\Modules\Subscriptions\Middleware\SubscriptionMiddleware;


class Subscription
{
    /**
     * Subscription constructor.
     */
    function __construct()
    {
    }

    public function subscriptions_middleware($middleware, $request)
    {
        $middleware[] = SubscriptionMiddleware::class;

        return $middleware;
    }

    /**
     * @param $dashboard_content
     * @return string
     * @throws \Throwable
     */
    public function dashboard_content1($dashboard_content, $active_tab)
    {
        if (user()->hasRole('superuser')) {
            $dashboard_content .= view('Subscriptions::partials.dashboard1')->with(compact('active_tab'))->render();
        } elseif (user()->hasPermissionTo('Subscriptions::subscriptions.subscribe')) {
            $dashboard_content .= view('Subscriptions::partials.member_dashboard')->with(compact('active_tab'))->render();
        }

        return $dashboard_content;
    }

    /**
     * @param $dashboard_content
     * @return string
     * @throws \Throwable
     */
    public function dashboard_content2($dashboard_content, $active_tab)
    {
        if (user()->hasRole('superuser')) {
            $dashboard_content .= view('Subscriptions::partials.dashboard2')->with(compact('active_tab'))->render();
        }

        return $dashboard_content;
    }

    /**
     * @param $user
     * @throws \Throwable
     */
    public function profile_subscriptions($user)
    {
        $profile_subscriptions = view('Subscriptions::partials.member_profile')->with(['user' => $user])->render();
        echo $profile_subscriptions;
    }


    public function set_subscription_active_tab($tab, $user)
    {
        if ($user->hasPermissionTo('Subscriptions::subscriptions.subscribe')) {
            $tab = 'subscriptions';
        }
        return $tab;
    }


    /**
     * @param $user
     */
    public function delete_customer_subscription($user)
    {
        try {
            if ($user->integration_id && $user->gateway) {
                $subscription = new SubscriptionsClass($user->gateway);
                $subscription->deleteCustomer($user);
            }
        } catch (\Exception $exception) {
            log_exception($exception);
        }
    }


    /**
     * @param $user
     * @throws \Throwable
     */
    public function show_profile_subscription_tabs($user, $active_tab)
    {
        if ($user->hasPermissionTo('Subscriptions::subscriptions.subscribe')) {
            $profile_subscription_tabs = view('Subscriptions::subscription.partials.tabs_items')->with(compact('user',
                'active_tab'))->render();
            echo $profile_subscription_tabs;
        }
    }

    /**
     * @param $user
     */
    public function show_profile_subscription_tabs_content($user, $active_tab)
    {
        $invoicesDataTable = new MyInvoicesDataTable();
        $invoicesDataTable->setResourceUrl(url('my-invoices'));

        $subscriptionCyclesDataTable = new MySubscriptionCyclesDataTable();
        $subscriptionCyclesDataTable->setResourceUrl('subscriptions/subscription-cycles/my-subscription-cycles');


        if ($user->hasPermissionTo('Subscriptions::subscriptions.subscribe')) {
            $profile_subscription_tabs = $invoicesDataTable
                ->render('Subscriptions::subscription.partials.tabs_content',
                    compact('user', 'active_tab'), [
                        'subscriptionCyclesDataTable' => $subscriptionCyclesDataTable->html(),
                        'invoicesDataTable' => $invoicesDataTable->html(),
                    ]);

            echo $profile_subscription_tabs;
            echo $invoicesDataTable->html()->assets();
            echo $subscriptionCyclesDataTable->html()->assets();

            \Actions::add_action('admin_footer_js', function () use ($invoicesDataTable, $subscriptionCyclesDataTable) {
                echo $invoicesDataTable->html()->scripts();
                echo $subscriptionCyclesDataTable->html()->scripts();
            }, 12);
        }
    }

}

