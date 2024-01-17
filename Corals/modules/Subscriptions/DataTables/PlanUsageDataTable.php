<?php

namespace Corals\Modules\Subscriptions\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Subscriptions\Models\Feature;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\Modules\Subscriptions\Transformers\PlanUsageTransformer;
use Yajra\DataTables\EloquentDataTable;
use Corals\Modules\Subscriptions\Models\PlanUsage;

class PlanUsageDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('subscriptions.models.plan_usage.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new PlanUsageTransformer());
    }

    /**
     * @param PlanUsage $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PlanUsage $model)
    {
        return $model->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'subscription' => ['title' => trans('Subscriptions::attributes.plan_usage.subscription')],
            'feature' => ['title' => trans('Subscriptions::attributes.plan_usage.feature')],
            'details' => ['title' => trans('Subscriptions::attributes.plan_usage.details')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'subscription.id' => [
                'title' => trans('Subscriptions::attributes.plan_usage.subscription'), 'class' => 'col-md-2', 'type' => 'select2-ajax',
                'model' => Subscription::class, 'columns' => ['subscription_reference'], 'active' => true
            ],

            'feature.id' => [
                'title' => trans('Subscriptions::attributes.plan_usage.feature'), 'class' => 'col-md-2', 'type' => 'select2-ajax',
                'model' => Feature::class, 'columns' => ['name', 'caption', 'description'], 'active' => true
            ],

            'created_at' => ['title' => trans('Corals::attributes.created_at'), 'class' => 'col-md-2', 'type' => 'date_range', 'active' => true],

        ];
    }

    protected function getOptions()
    {
        return [
            'has_action' => false,
        ];
    }
}
