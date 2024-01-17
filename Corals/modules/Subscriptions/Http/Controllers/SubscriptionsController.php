<?php

namespace Corals\Modules\Subscriptions\Http\Controllers;

use Corals\Foundation\DataTables\CoralsBuilder;
use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Payment\Payment;
use Corals\Modules\Subscriptions\Classes\Subscription;
use Corals\Modules\Subscriptions\DataTables\SubscriptionsDataTable;
use Corals\Modules\Subscriptions\Facades\SubscriptionsManager;
use Corals\Modules\Subscriptions\Http\Requests\SubscriptionCheckoutRequest;
use Corals\Modules\Subscriptions\Http\Requests\SubscriptionRequest;
use Corals\Modules\Subscriptions\Models\Plan;
use Corals\Modules\Subscriptions\Models\Product;
use Corals\Modules\Subscriptions\Models\Subscription as SubscriptionModel;
use Corals\Modules\Subscriptions\Services\SubscriptionService;
use Corals\User\Models\User;
use Illuminate\Http\Request;

class SubscriptionsController extends BaseController
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;

        $this->resource_url = config('subscriptions.models.subscription.resource_url');

        $this->title = 'Subscriptions::module.subscription.title';

        $this->title_singular = 'Subscriptions::module.subscription.title_singular';

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param SubscriptionModel $subscription
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, SubscriptionModel $subscription)
    {
        $this->checkAccess();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => $subscription->subscription_reference]),
            'showModel' => $subscription,
        ]);

        CoralsBuilder::DataTableScripts();

        return view('Subscriptions::subscription.show')->with(compact('subscription'));
    }

    private function checkAccess()
    {
        if (!user()->hasPermissionTo('Subscriptions::subscriptions.view')) {
            abort(403);
        }
    }

    public function index(Request $request, SubscriptionsDataTable $dataTable)
    {
        $this->checkAccess();

        return $dataTable->render('Subscriptions::subscription.index');
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pricing(Request $request, Product $product)
    {
        $this->setViewSharedData([
            'title' => trans('Subscriptions::module.plan.title'),
            'hide_sidebar' => true
        ]);

        $products = $this->subscriptionService->getPricingProducts($request, $product);

        return view('Subscriptions::subscription.pricing')->with(compact('products'));
    }

    public function confirmationPage($message, $continueUrl = null)
    {
        $this->setViewSharedData([
            'title' => trans('Subscriptions::module.confirmation.title'),
            'hide_sidebar' => true
        ]);

        return view('Subscriptions::subscription.confirmation')->with(compact('message', 'continueUrl'));
    }

    public function statusPage(Request $request, SubscriptionModel $subscription = null)
    {
        $this->setViewSharedData([
            'title' => trans('Corals::attributes.status'),
            'hide_sidebar' => true
        ]);

        $message = $request->get('message', false);

        if (!$message) {
            abort(404);
        }

        $gatewayPaymentDetails = '';

        if ($subscription) {
            $gateway = $subscription->gateway();

            if ($gateway) {
                $gatewayPaymentDetails = $gateway->getPaymentDetails($subscription);
            }
        }

        return view('Subscriptions::subscription.status')->with(compact('message', 'gatewayPaymentDetails'));
    }

    /**
     * @param Request $request
     * @param Plan $plan
     * @param User|null $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function cancel(Request $request, Plan $plan, User $user = null)
    {
        $confirmed = $request->get('confirmed', false);

        if (!$confirmed) {
            $message = trans('Subscriptions::exception.subscription.cancel_subscribe_plan', ['name' => $plan->name]);
            return $this->confirmationPage($message);
        }

        try {
            $message = $this->subscriptionService->cancelPlanSubscription($request, $plan, $user);

            return redirect('subscriptions/status?message=' . urlencode($message));
        } catch (\Exception $exception) {
            log_exception($exception, 'SubscriptionsController', 'cancel');
        }

        return redirect('dashboard');
    }

    /**
     * @param Request $request
     * @param Plan $plan
     * @param User|null $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function checkout(Request $request, Plan $plan, User $user = null)
    {
        try {
            $gateway_name = null;
            $gateway = null;

            if (is_null($user)) {
                $user = user();
            }
            //Check if its Subscription Swap
            $current_plan = null;
            if ($plan) {
                if ($user->subscribed($plan->product_id)) {
                    $current_plan_subscription = $user->currentSubscription($plan->product_id);
                    if (\Payments::isGatewaySupported($current_plan_subscription->gateway)) {
                        $gateway_name = $current_plan_subscription->gateway;
                    }
                    $current_plan = $current_plan_subscription->plan;
                }
            }
            if (!$gateway_name) {
                $available_gateways = \Payments::getAvailableGateways();
                if (count($available_gateways) == 1) {
                    $gateway_name = key($available_gateways);
                }
            }

            /**
             * check if user gateway null
             * or
             * if user gateway not in supported gateways in case of supported gateways changed
             */

            $this->setViewSharedData([
                    'title' => trans('Subscriptions::module.checkout_details.title'),
                    'hide_sidebar' => true
                ]
            );

            if ($gateway_name) {
                $subscription = new Subscription($gateway_name);
                $gateway = $subscription->gateway;
            }

            $billing_address = [];
            $shipping_address = [];
            $enable_shipping = $plan->product->require_shipping_address;

            if (!$billing_address) {
                if (user()->address('billing')) {
                    $billing_address = user()->address('billing');
                }
            }


            if (user()->address('shipping')) {
                $shipping_address = user()->address('shipping');
            }

            \Assets::add(asset('assets/corals/plugins/smartwizard/css/smart_wizard.min.css'));
            \Assets::add(asset('assets/corals/plugins/smartwizard/css/smart_wizard_theme_arrows.css'));
            \Assets::add(asset('assets/corals/plugins/smartwizard/js/jquery.smartWizard.min.js'));
            \Assets::add(asset('assets/corals/plugins/smartwizard/js/validator.min.js'));

            $require_payment_step = true;
            if ($gateway) {
                if (!$gateway->userRequirePayment($user)) {
                    $require_payment_step = false;
                }
            }
            if ($plan->free_plan) {
                $require_payment_step = false;
            }

            return view('Subscriptions::subscription.checkout')->with(compact('require_payment_step', 'enable_shipping',
                'shipping_address', 'billing_address', 'plan', 'current_plan', 'gateway', 'user'));
        } catch (\Exception $exception) {
            log_exception($exception, 'SubscriptionController', 'checkout');
            return redirect('profile');
        }
    }

    /**
     * @param Request $request
     * @param User|null $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function paymentConfiguration(Request $request, User $user = null)
    {
        if (is_null($user)) {
            $user = user();
        }
        try {
            $gateway_name = null;
            if (!is_null($user->gateway) && \Payments::isGatewaySupported($user->gateway)) {
                $gateway_name = $user->gateway;
            } else {
                throw new \Exception(trans('Subscriptions::exception.subscription.invalid_user_gateway'));
            }

            $this->setViewSharedData([
                'title' => trans('Subscriptions::module.payment_details.title'),
                'hide_sidebar' => true
            ]);

            $subscription = new Subscription($gateway_name);
            $gateway = $subscription->gateway;

            return view('Subscriptions::subscription.payment_config')->with(compact('gateway', 'user'));
        } catch (\Exception $exception) {
            log_exception($exception, 'SubscriptionController', 'payment-configuration');
            return redirect('profile');
        }
    }


    /**
     * @param $gateway
     * @param Plan $plan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function gatewayPayment($gateway, Plan $plan)
    {
        try {
            $subscription = new Subscription($gateway);
            $action = 'subscriptions/do-checkout/' . $plan->hashed_id;
            $gateway = $subscription->gateway;
            $view = $gateway->getPaymentViewName('subscription');
            return view($view)->with(compact('plan', 'gateway', 'action'));
        } catch (\Exception $exception) {
            log_exception($exception, 'SubscriptionController', 'card');
        }
    }


    /**
     * @param $gateway
     * @param Plan $plan
     * @param User $user
     * @return mixed
     */
    public function gatewaySubscriptionToken($gateway, Plan $plan, User $user)
    {
        if (is_null($user)) {
            $user = user();
        }
        try {
            $subscription = new Subscription($gateway);
            $token = $subscription->createSubscriptionToken($plan, $user);
            return $token;
        } catch (\Exception $exception) {
            log_exception($exception, 'SubscriptionController', 'generateSubscriptionToken');
        }
    }

    /**
     * @param SubscriptionCheckoutRequest $request
     * @param Plan $plan
     * @param User|null $user
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     * @throws \Exception
     */
    public function doCheckout(SubscriptionCheckoutRequest $request, Plan $plan, User $user = null)
    {
        $gateway = $request->gateway;
        $subscription_data = $request->only('billing_address', 'shipping_address');
        $user_data = $request->only('card_last_four', 'card_brand');
        if (is_null($user)) {
            $user = user();
        }
        $subscription = null;

        if ($gateway) {
            $checkoutToken = $request->checkoutToken;

            session()->put('checkoutToken', $checkoutToken);


            // $user->gateway = $gateway;
            //$user->save();
            $subscription = new Subscription($gateway);

            if ($subscription->gateway->getConfig('create_remote_customer')) {
                if (is_null($user->integration_id)) {
                    try {
                        $subscription->createCustomer($user, [
                            'checkoutToken' => $checkoutToken,
                            'billing_address' => $request->billing_address,
                            'shipping_address' => $request->shipping_address
                        ]);
                    } catch (\Exception $exception) {
                        log_exception($exception, 'SubscriptionController', 'createCustomer');
                        return redirectTo('subscriptions/checkout/' . $plan->hashed_id);
                    }
                } else {
                    try {
                        $subscription->updateCustomer($user, [
                            'checkoutToken' => $checkoutToken,
                            'billing_address' => $request->billing_address,
                            'shipping_address' => $request->shipping_address
                        ]);
                    } catch (\Exception $exception) {
                        log_exception($exception, 'SubscriptionController', 'updateCustomer:q!');
                        return redirectTo('subscriptions/checkout/' . $plan->hashed_id);
                    }
                }
            }
        }
        try {
            if ($plan->exists) {
                if ($gateway) {
                    $subscription = new Subscription($gateway);
                    if (!$subscription->isValidSubscription($plan, $user)) {
                        //Payment Details are missing
                        return redirect('subscriptions/checkout/' . $plan->hashed_id);
                    }
                }

                $user_subscription = $this->subscriptionService->subscribe($plan, $subscription, $user,
                    $subscription_data);

                if ($user_data) {
                    //save address to user section
                    $user->update($user_data);
                }

                if ($request->input('save_billing')) {
                    $user->saveAddress($request->input('billing_address'), 'billing');
                }
                if ($request->input('save_shipping')) {
                    $user->saveAddress($request->input('shipping_address'), 'shipping');
                }

                //status page
                $subscription_message = $this->subscriptionService->getSubscriptionCreationMessage($user_subscription);

                return redirectTo('subscriptions/status/' . $user_subscription->hashed_id . '?message=' . urlencode($subscription_message));
            } else {
                flash(trans('Corals::messages.success.saved',
                    ['item' => trans('Subscriptions::module.card.title')]))->success();
                return redirectTo('profile');
            }
        } catch (\Exception $exception) {
            log_exception($exception, 'SubscriptionsController', 'subscribe');
            return redirectTo('profile');
        }
    }

    /**
     * @param Request $request
     * @param User|null $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function saveCard(Request $request, User $user = null)
    {
        $checkoutToken = $request->checkoutToken;
        $card_last_four = $request->card_last_four;
        $card_brand = $request->card_brand;
        $gateway = $request->gateway;
        session()->put('checkoutToken', $checkoutToken);

        if (is_null($user)) {
            $user = user();
        }

        $user->gateway = $gateway;

        $user->save();

        $subscription = new Subscription($gateway);

        if ($subscription->gateway->getConfig('create_remote_customer')) {
            if (is_null($user->integration_id)) {
                try {
                    $subscription->createCustomer($user, [
                        'checkoutToken' => $checkoutToken,
                        'billing_address' => $user->address('billing'),
                        'shipping_address' => $user->address('shipping')
                    ]);
                } catch (\Exception $exception) {
                    log_exception($exception, 'SubscriptionController', 'saveCard');
                }
            } else {
                try {
                    $subscription->updateCustomer($user, [
                        'checkoutToken' => $checkoutToken,
                        'billing_address' => $user->address('billing'),
                        'shipping_address' => $user->address('shipping')
                    ]);
                } catch (\Exception $exception) {
                    log_exception($exception, 'SubscriptionController', 'saveCard');
                }
            }
        }

        $user->update([
            'card_last_four' => $card_last_four,
            'card_brand' => $card_brand,
        ]);

        flash(trans('Corals::messages.success.saved',
            ['item' => trans('Subscriptions::module.payment_details.title_singular')]))->success();

        return redirect('profile');
    }

    /**
     * @param SubscriptionRequest $request
     * @param SubscriptionModel $subscription
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SubscriptionRequest $request, SubscriptionModel $subscription)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $subscription->subscription_reference])
        ]);

        if (\Payments::isGatewaySupported($subscription->gateway)) {
            $gateway = Payment::create($subscription->gateway);

            if ($gateway->getConfig('offline_management') && is_null($subscription->next_billing_at)) {
                $subscription->next_billing_at = $subscription->created_at->addDays($subscription->remainingDays());
            }
        }


        return view('Subscriptions::subscription.create_edit')->with(compact('subscription'));
    }

    /**
     * @param SubscriptionModel $subscription
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(SubscriptionModel $subscription)
    {
        return view('Subscriptions::subscription.create_edit')->with(compact('subscription'));
    }


    /**
     * @param SubscriptionRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SubscriptionRequest $request)
    {
        try {
            $data = $request->except('mark_invoice_as_paid');
            $subscription = SubscriptionModel::create($data);
            if ($subscription->status == "canceled") {
                $subscription->markAsCancelled();
            } elseif ($subscription->status == "active") {
                if ($request->get('mark_invoice_as_paid')) {
                    SubscriptionsManager::activeSubscriptionWithInvoice($subscription);
                }

                $user = $subscription->user;
                event('notifications.subscription.created', ['user' => $user, 'subscription' => $subscription]);
            }

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, SubscriptionModel::class, 'create');
        }

        return redirectTo($this->resource_url . '/' . $subscription->hashed_id);
    }

    /**
     * @param SubscriptionRequest $request
     * @param SubscriptionModel $subscription
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(SubscriptionRequest $request, SubscriptionModel $subscription)
    {
        try {
            $data = $request->except('mark_invoice_as_paid');
            $old_status = $subscription->status;
            $new_status = $data['status'];
            $subscription->update($data);
            if ($old_status != $new_status) {
                if ($new_status == "canceled") {
                    $subscription->markAsCancelled();
                } elseif ($new_status == "active" && $old_status == "pending") {
                    if ($request->get('mark_invoice_as_paid')) {
                        SubscriptionsManager::activeSubscriptionWithInvoice($subscription);
                    }

                    $user = $subscription->user;
                    event('notifications.subscription.approved', ['user' => $user, 'subscription' => $subscription]);
                }
            }
            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, SubscriptionModel::class, 'update');
        }
        return redirectTo($this->resource_url . '/' . $subscription->hashed_id);
    }

    /**
     * @param SubscriptionModel $subscription
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createInvoice(SubscriptionModel $subscription)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Subscriptions::labels.subscription.create_invoice',
                ['name' => $subscription->subscription_reference])
        ]);

        $invoicable_resource_url = $this->resource_url;

        $this->setViewSharedData(['resource_url' => config('payment_common.models.invoice.resource_url')]);

        $invoicable = $subscription;

        $invoice = new Invoice();

        $invoice->due_date = now();
        $invoice->invoice_date = now();

        return view('Payment::invoices.create_edit')->with(compact('invoice', 'invoicable', 'invoicable_resource_url'));
    }

    /**
     * @param Request $request
     * @param SubscriptionModel $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function markInvoiceAsPaidAndActive(Request $request, SubscriptionModel $subscription)
    {
        try {
            abort_if(
                user()->cant('markInvoiceAsPaidAndActive', $subscription),
                403,
                trans("Subscriptions::exception.subscription.dont_have_permission_to_update_subscription")
            );

            SubscriptionsManager::activeSubscriptionWithInvoice($subscription);

            $message = [
                'message' => trans('Subscriptions::exception.subscription.subscription_successfully_paid_and_activated'),
                'level' => 'success'
            ];
        } catch (\Exception $exception) {
            $message = [
                'message' => $exception->getMessage(),
                'level' => 'error'
            ];

            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }

    /**
     * @param Request $request
     * @param SubscriptionModel $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function renewSubscription(Request $request, SubscriptionModel $subscription)
    {
        try {
            $this->subscriptionService->renewSubscription($subscription);

            $subscription->refresh();

            $message = [
                'message' => trans('Subscriptions::labels.subscription.subscription_renewed_successfully', [
                    'subscription_reference' => $subscription->subscription_reference
                ]),
                'level' => 'success',
            ];
            if ($request->has('redirect')) {
                $message = ['invoice_link' => $subscription->invoice->getShowURL()];
                flash(trans('Subscriptions::labels.subscription.subscription_renewed_successfully', [
                    'subscription_reference' => $subscription->subscription_reference
                ]))->success();
            }
        } catch (\Exception $exception) {
            $message = [
                'message' => $exception->getMessage(),
                'level' => 'error',
            ];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }
}
