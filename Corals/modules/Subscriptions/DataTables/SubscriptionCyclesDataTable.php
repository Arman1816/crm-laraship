<?php

namespace Corals\Modules\Subscriptions\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\Modules\Subscriptions\Models\SubscriptionCycle;
use Corals\Modules\Subscriptions\Transformers\SubscriptionCycleTransformer;
use Yajra\DataTables\EloquentDataTable;

class SubscriptionCyclesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('subscriptions.models.subscription_cycle.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new SubscriptionCycleTransformer());
    }

    /**
     * @param SubscriptionCycle $model
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function query(SubscriptionCycle $model)
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
            'subscription' => ['title' => trans('Subscriptions::attributes.subscription_cycle.subscription')],
            'starts_at' => ['title' => trans('Subscriptions::attributes.subscription_cycle.starts_at')],
            'ends_at' => ['title' => trans('Subscriptions::attributes.subscription_cycle.ends_at')],

            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'subscription.id' => [
                'title' => trans('Subscriptions::attributes.subscription_cycle.subscription'), 'class' => 'col-md-2', 'type' => 'select2-ajax',
                'model' => Subscription::class, 'columns' => ['subscription_reference'], 'active' => true
            ],

            'starts_at' => ['title' => trans('Subscriptions::attributes.subscription_cycle.starts_at'), 'class' => 'col-md-2', 'type' => 'date', 'active' => true],
            'ends_at' => ['title' => trans('Subscriptions::attributes.subscription_cycle.ends_at'), 'class' => 'col-md-2', 'type' => 'date', 'active' => true],
            'created_at' => ['title' => trans('Corals::attributes.created_at'), 'class' => 'col-md-2', 'type' => 'date', 'active' => true],
        ];
    }

    protected function getOptions()
    {
        return [
            'has_action' => false,
        ];
    }
}
