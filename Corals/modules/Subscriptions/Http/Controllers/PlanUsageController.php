<?php

namespace Corals\Modules\Subscriptions\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Subscriptions\DataTables\PlanUsageDataTable;
use Corals\Modules\Subscriptions\Http\Requests\PlanUsageRequest;

class PlanUsageController extends BaseController
{

    public function __construct()
    {
        $this->resource_url = config('subscriptions.models.plan_usage.resource_url');

        $this->title = trans('Subscriptions::module.plan_usage.title');
        $this->title_singular = trans('Subscriptions::module.plan_usage.title_singular');

        $this->setViewSharedData(['hideCreate' => true]);

        parent::__construct();
    }

    /**
     * @param PlanUsageRequest $request
     * @param PlanUsageDataTable $dataTable
     * @return mixed
     */
    public function index(PlanUsageRequest $request, PlanUsageDataTable $dataTable)
    {
        return $dataTable->render('Subscriptions::plan_usage.index');
    }
}
