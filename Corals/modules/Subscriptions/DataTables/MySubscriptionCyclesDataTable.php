<?php

namespace Corals\Modules\Subscriptions\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Subscriptions\Models\Subscription;
use Corals\Modules\Subscriptions\Models\SubscriptionCycle;
use Corals\Modules\Subscriptions\Transformers\SubscriptionCycleTransformer;
use Yajra\DataTables\EloquentDataTable;

class MySubscriptionCyclesDataTable extends BaseDataTable
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
        return $model->newQuery()->whereHas('subscription', function ($query) {
            $query->where('subscriptions.user_id', user()->id);
        })->latest('id');
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
            'subscription' => [
                'title' => trans('Subscriptions::attributes.subscription_cycle.subscription'),
                'orderable' => false,
                'searchable' => false
            ],
            'starts_at' => ['title' => trans('Subscriptions::attributes.subscription_cycle.starts_at')],
            'ends_at' => ['title' => trans('Subscriptions::attributes.subscription_cycle.ends_at')],
            'current_cycle' => [
                'title' => trans('Subscriptions::labels.subscription_cycle.current_cycle'),
                'orderable' => false,
                'searchable' => false
            ],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'subscription.id' => [
                'title' => trans('Subscriptions::attributes.subscription_cycle.subscription'),
                'class' => 'col-md-2',
                'type' => 'select2-ajax',
                'model' => Subscription::class,
                'columns' => ['subscription_reference'],
                'active' => true
            ],
            'starts_at' => [
                'title' => trans('Subscriptions::attributes.subscription_cycle.starts_at'),
                'class' => 'col-md-2',
                'type' => 'date',
                'active' => true
            ],
            'ends_at' => [
                'title' => trans('Subscriptions::attributes.subscription_cycle.ends_at'),
                'class' => 'col-md-2',
                'type' => 'date',
                'active' => true
            ],
        ];
    }

    protected function getOptions()
    {
        return [
            'has_action' => false,
        ];
    }
}
