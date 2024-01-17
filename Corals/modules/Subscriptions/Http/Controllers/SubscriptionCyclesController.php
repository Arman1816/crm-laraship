<?php

namespace Corals\Modules\Subscriptions\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Subscriptions\DataTables\MySubscriptionCyclesDataTable;
use Corals\Modules\Subscriptions\DataTables\SubscriptionCyclesDataTable;
use Corals\Modules\Subscriptions\Http\Requests\SubscriptionCycleRequest;
use Illuminate\Http\Request;

class SubscriptionCyclesController extends BaseController
{

    public function __construct()
    {
        $this->resource_url = config('subscriptions.models.subscription_cycle.resource_url');

        $this->title = trans('Subscriptions::module.subscription_cycle.title');
        $this->title_singular = trans('Subscriptions::module.subscription_cycle.title_singular');

        $this->setViewSharedData(['hideCreate' => true]);

        parent::__construct();
    }

    /**
     * @param SubscriptionCycleRequest $request
     * @param SubscriptionCyclesDataTable $dataTable
     * @return mixed
     */
    public function index(SubscriptionCycleRequest $request, SubscriptionCyclesDataTable $dataTable)
    {
        return $dataTable->render('Subscriptions::subscription_cycles.index');
    }

    /**
     * @param Request $request
     * @param MySubscriptionCyclesDataTable $mySubscriptionCyclesDataTable
     * @return mixed
     */
    public function mySubscriptionCycles(Request $request, MySubscriptionCyclesDataTable $mySubscriptionCyclesDataTable)
    {
        return $mySubscriptionCyclesDataTable->renderAjaxAndActions();
    }
}
