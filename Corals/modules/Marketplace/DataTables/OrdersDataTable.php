<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Transformers\OrderTransformer;
use Yajra\DataTables\EloquentDataTable;

class OrdersDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.order.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new OrderTransformer());
    }

    /**
     * @param Order $model
     * @return \Illuminate\Database\Query\Builder
     */
    public function query(Order $model)
    {
        return $model->with('user')->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            'id' => ['visible' => false],
            'order_number' => ['title' => trans('Marketplace::attributes.order.order_number')],
            'amount' => ['title' => trans('Marketplace::attributes.order.amount')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'payment_status' => ['title' => trans('Marketplace::attributes.order.payment_status'), 'orderable' => false, 'searchable' => false],
            'user_id' => ['title' => trans('Marketplace::attributes.order.user_id')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')]
        ];

        $columns = \Store::getStoreColumns($columns, 'order');

        return $columns;
    }

    public function getFilters()
    {

        $filters = [
            'user.name' => [
                'title' => trans('User::attributes.user.name'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'user.last_name' => [
                'title' => trans('User::attributes.user.last_name'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'created_at' => [
                'title' => trans('Corals::attributes.created_at'),
                'class' => 'col-md-4',
                'type' => 'date_range',
                'active' => true
            ],
            'status' => [
                'title' => trans('Marketplace::attributes.order.status_order'),
                'class' => 'col-md-2',
                'options' => trans(config('marketplace.models.order.statuses')),
                'type' => 'select',
                'active' => true
            ]
        ];


        $filters = \Store::getStoreFilters($filters);

        return $filters;
    }

    protected function getBulkActions()
    {
        return [
            'pending' => ['title' => '<i class="fa fa-clock-o" aria-hidden="true"></i> ' . trans('Marketplace::status.order.pending'), 'permission' => 'Marketplace::order.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'processing' => ['title' => '<i class="fa fa-cog"></i> ' . trans('Marketplace::status.order.processing'), 'permission' => 'Marketplace::order.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'completed' => ['title' => '<i class="fa fa-check"></i> ' . trans('Marketplace::status.order.completed'), 'permission' => 'Marketplace::order.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'canceled' => ['title' => '<i class="fa fa-close"></i>  ' . trans('Marketplace::status.order.canceled'), 'permission' => 'Marketplace::order.update', 'confirmation' => trans('Corals::labels.confirmation.title')]
        ];
    }

    protected function getOptions()
    {
        $url = url(config('marketplace.models.order.resource_url'));
        return ['has_action' => true, 'resource_url' => $url];
    }



}
