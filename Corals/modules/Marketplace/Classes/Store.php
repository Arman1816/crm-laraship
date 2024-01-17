<?php


namespace Corals\Modules\Marketplace\Classes;

use Corals\Foundation\Search\Search;
use Corals\Modules\Marketplace\Models\Store as StoreModel;
use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\Settings\Facades\Settings;
use Corals\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Store
{


    protected $store;

    public $page_limit;

    public function __construct()
    {
        $this->page_limit = Settings::get('marketplace_appearance_page_limit', 15);
    }


    public function getTopRatedStores($limit = 6, $minAvg = 4)
    {
        $stores = StoreModel::active()->WithoutMain();
        $stores = $stores->leftJoin('utility_avg_ratings', function ($join) {
            $join->on('avgreviewable_ID', '=', 'marketplace_stores.id')
                ->where('utility_avg_ratings.avgreviewable_type', getMorphAlias(StoreModel::class));
        })->where('avg', '>=', $minAvg)
            ->orderBy('avg', 'desc')
            ->orderBy('count', 'desc');

        $stores = $stores->select('marketplace_stores.*')->limit($limit)->get();
        return $stores;
    }

    /**
     * @param Request $request
     * @return mixed
     */

    public function getStores(Request $request)
    {
        $stores = $this->storesPublicBaseQuery();

        foreach ($request->all() as $filter => $value) {
            $filterMethod = $filter . 'QueryBuilderFilter';
            if (method_exists($this, $filterMethod) && !empty($value)) {
                $stores = $this->{$filterMethod}($stores, $value);
            }
        }

        $stores = $stores->addSelect('marketplace_stores.*')->paginate($this->page_limit);

        return $stores;
    }

    protected function storesPublicBaseQuery($store = null)
    {
        $query = StoreModel::active()->withoutMain();
        return $query;
    }

    protected function sortQueryBuilderFilter($stores, $sortOption)
    {
        switch ($sortOption) {
            case 'average_rating':
                $stores = $stores->leftJoin('utility_avg_ratings', function ($join) {
                    $join->on('avgreviewable_ID', '=', 'marketplace_stores.id')
                        ->where('utility_avg_ratings.avgreviewable_type', getMorphAlias(StoreModel::class));
                })->orWhereNull('utility_avg_ratings.id')
                    ->orderBy('avg', 'desc');
                break;
            case 'a_z_order':
                $stores = $stores->orderBy('marketplace_stores.name', 'asc');
                break;
            case 'z_a_order':
                $stores = $stores->orderBy('marketplace_stores.name', 'desc');
                break;
            case 'recently_added':
                $stores = $stores->orderBy('marketplace_stores.created_at', 'desc');
                break;
        }
        return $stores;
    }

    protected function searchQueryBuilderFilter($stores, $search_term)
    {
        $search = new Search();

        $config = [
            'title_weight' => \Settings::get('marketplace_search_title_weight'),
            'content_weight' => \Settings::get('marketplace_search_content_weight'),
            'enable_wildcards' => \Settings::get('marketplace_search_enable_wildcards')
        ];

        $stores = $search->AddSearchPart($stores, $search_term, StoreModel::class, $config);

        return $stores;
    }

    public function getCurrentStore($request, $user = null)
    {
        $enable_domain_parking = \Settings::get('marketplace_general_enable_domain_parking', false);
        $enable_subdomain = \Settings::get('marketplace_general_enable_subdomain', false);
        $domain = parse_url($request->url(), PHP_URL_HOST);

        if ($enable_subdomain) {
            $url_array = explode('.', $domain);
            $slug = $url_array[0];
        } else {
            $slug = $request->route('slug');
        }
        if ($slug) {
            $store = StoreModel::where('slug', $slug)->first();
            if ($store) {
                return $store;
            }
        }
        if ($enable_domain_parking) {
            $store = StoreModel::where('parking_domain', $domain)->first();
            if ($store) {
                return $store;
            }
        }


        if (session()->get('current_store')) {
            $store = StoreModel::find(session()->get('current_store'));
            return $store;
        }

        return false;
    }


    public function getFeaturedStores()
    {
        $products = StoreModel::active()->featured()->get();

        return $products;
    }

    function accessableStores($user = null)
    {
        if (!$user) {
            $user = user();
        }
        if ($this->isStoreAdmin()) {
            $stores = StoreModel::all();
        } else {
            $stores = user()->stores;
        }
        return $stores;
    }

    function accessableStoresList($user = null)
    {
        $stores = $this->accessableStores($user);

        $stores = $stores->pluck('name', 'id');

        return $stores;
    }

    function showStoreSelection()
    {
        if (!user()) {
            return;
        }

        if ($this->isStoreAdmin()) {
            return;
        }


        $stores = StoreModel::where('user_id', user()->id)->get();
        if (count($stores) <= 1) {
            return;
        }

        $current_store = null;


        $current_store = $this->getStore();

        $stores_dropdown = view('Marketplace::partials.store_selection')->with(compact('ul_class', 'li_class', 'stores',
            'current_store'))->render();
        echo $stores_dropdown;
    }

    /**
     * @return \Corals\Modules\Marketplace\Models\Store
     */
    public function getStore()
    {
        return $this->store;
    }


    /**
     * @return \Corals\Modules\Marketplace\Models\Store
     */
    public function getVendorStore($user = null)
    {
        if (!$user) {
            $user = user();
        }
        return $this->store ?? StoreModel::where('user_id', $user->id)->first();
    }

    /**
     * @return null
     */
    public function setStore($store)
    {
        return $this->store = $store;
    }

    public function isStoreAdmin(User $user = null)
    {
        if (is_null($user)) {
            $user = user();
        }

        if (!$user) {
            return false;
        }

        return $user->hasPermissionTo('Administrations::admin.marketplace');
    }

    public function getStoreFilters($filters)
    {
        if ($this->accessableStoresList()->count() > 1) {
            $filters = array_merge([
                'store.id' => [
                    'title' => trans('Marketplace::attributes.product.store'),
                    'class' => 'col-md-2',
                    'type' => 'select2',
                    'options' => \Store::accessableStoresList(),
                    'active' => true
                ],
            ], $filters);
        }
        return $filters;
    }

    public function getStoreColumns($columns, $view = null)
    {
        if (($this->accessableStoresList()->count() > 1) && !$this->getStore()) {
            $columns['store'] = [
                'title' => trans('Marketplace::attributes.product.store'),
                'orderable' => false,
                'searchable' => false
            ];
        }

        return $columns;
    }

    public function getStoreFields($model, $required = false, $parent = '')
    {
        if (\Store::isStoreAdmin()) {
            return \CoralsForm::select('store_id', 'Marketplace::attributes.product.store', [], $required, null,
                [
                    'class' => 'select2-ajax',
                    'data' => [
                        'model' => \Corals\Modules\Marketplace\Models\Store::class,
                        'columns' => json_encode(['name', 'user_id']),
                        'selected' => json_encode(optional($model)->store_id ? [optional($model)->store_id] : []),
                        'where' => json_encode([]),
                        'select2_parent' => $parent
                    ]
                ], 'select2');
        } else {
            return '<div class="form-group"><span data-name="store_id"></span></div>';
        }
    }

    public function setStoreData($data)
    {
        if (!isset($data['store_id'])) {
            $store = \Store::getVendorStore();
            if (!$store) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'store_id' => ['Unable to specify Store to attach object to'],
                ]);
                throw $error;
            }

            $data['store_id'] = $store->id;
        }
        return $data;
    }

    public function createStore($user = null, Subscription $subscription = null)
    {
        if (!$user) {
            $user = user();
        }
        $store = new StoreModel();
        $store->user_id = $user->id;
        $store->name = $user->full_name;
        if ($subscription) {
            $store->causer_id = $subscription->id;
            $store->causer_type = Subscription::class;
        }

        $store->save();

        $vendor_role = \Settings::get('marketplace_general_vendor_role', '');
        if ($vendor_role && !$user->hasRole($vendor_role)) {
            $user->assignRole($vendor_role);
        }

        $store->advertiser()->create([
            'name' => $store->name,
            'contact' => $store->user->full_name,
            'email' => $store->user->email,
            'status' => 'active',
        ]);
    }

    public function getTransactionsSummary($user = null)
    {
        if (!$user) {
            $user = user();
        }

        if ($this->isStoreAdmin()) {
            $total_sales = Transaction::completed()->where('type', 'order_revenue')->sum('amount');
            $total_commission = Transaction::completed()->where('type', 'commission')->sum('amount') * -1;
            $total_completed_withdrawals = Transaction::completed()->where('type', 'withdrawal')->sum('amount') * -1;
            $total_pending_withdrawals = Transaction::pending()->where('type', 'withdrawal')->sum('amount') * -1;

            $profit = Transaction::completed()->where('amount', '>', 0)->sum('amount');
            $deductions = Transaction::whereIn('status', ['completed', 'prending'])->where('amount', '<',
                0)->sum('amount');
            $balance = $profit - $deductions;
        } else {
            $total_sales = $user->transactions()->completed()->where('type', 'order_revenue')->sum('amount');
            $total_commission = $user->transactions()->completed()->where('type', 'commission')->sum('amount') * -1;
            $total_completed_withdrawals = $user->transactions()->completed()->where('type',
                    'withdrawal')->sum('amount') * -1;
            $total_pending_withdrawals = $user->transactions()->pending()->where('type',
                    'withdrawal')->sum('amount') * -1;

            $profit = $user->transactions()->completed()->where('amount', '>', 0)->sum('amount');
            $deductions = $user->transactions()->whereIn('status', ['completed', 'pending'])->where('amount', '<',
                0)->sum('amount');
            $balance = $profit + $deductions;
        }


        return compact('total_sales', 'total_commission', 'total_completed_withdrawals', 'total_pending_withdrawals',
            'balance');
    }

    public function getStoreCommissionAmount($store, $amount)
    {
        $commission_amount = 0;

        if ($store->custom_commission) {
            $commission_amount = $amount * $store->custom_commission / 100;
        } else {
            $storeOwner = $store->user;
            $vendor_require_subscription = \Settings::get('marketplace_general_vendor_require_subscription', false);

            if ($vendor_require_subscription) {
                $marketplaceSubscriptionProduct = \Settings::get('marketplace_general_subscription_product', '');
                $marketplaceCommissionFeatureId = \Settings::get('marketplace_general_commission_feature', '');

                if ($marketplaceSubscriptionProduct && $marketplaceCommissionFeatureId) {
                    $storeSubscription = $storeOwner->currentSubscription($marketplaceSubscriptionProduct);
                    $subscriptionCommission = $storeSubscription->plan->features()->where('feature_id',
                        $marketplaceCommissionFeatureId)->first();
                    if ($subscriptionCommission) {
                        $commission_amount = $subscriptionCommission->pivot->value;
                    } else {
                        $commission_amount = 0;
                    }
                }
            } else {
                $fixed_commission_percentage = \Settings::get('marketplace_general_fixed_commission_percentage', 0);
                if ($fixed_commission_percentage) {
                    $commission_amount = $amount * ($fixed_commission_percentage / 100);
                }
            }
        }
        return $commission_amount;
    }

    public function getStoreShippableCountries($store = null)
    {
        $shippingCountries = [];
        $allCountries = \Settings::getCountriesList();

        if ($store) {
            $shippingCountries = Arr::only($allCountries,
                $store->getSettingValue('marketplace_shipping_shippable_countries') ?? []
            );
        }

        if (empty($shippingCountries)) {
            $shippingCountries = $allCountries;
        }

        return $shippingCountries;
    }
}
