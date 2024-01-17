<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Transformers\OrderTransformer;
use Yajra\DataTables\EloquentDataTable;

class MyStoreOrdersDataTable extends BaseDataTable
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
     * Get query source of dataTable.
     * @param Order $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Order $model)
    {
        if (\Store::isStoreAdmin()) {
            return $model->newQuery();

        } else {
            return user()->storeOrders()->select('marketplace_orders.*')->newQuery();
        }

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
            'payment_status' => ['title' => trans('Marketplace::attributes.order.payment_status'),'orderable' => false, 'searchable' => false],
            'created_at' => ['title' => trans('Corals::attributes.created_at')]
        ];
        $columns = \Store::getStoreColumns($columns);
        return $columns;
    }

    protected function getOptions()
    {
        return ['has_action' => true];
    }
}
