<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Models\Shipping;
use Corals\Modules\Marketplace\Transformers\ShippingTransformer;
use Yajra\DataTables\EloquentDataTable;

class ShippingsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.shipping.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new ShippingTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Shipping $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Shipping $model)
    {
        if (Store::isStoreAdmin()) {
            return $model->newQuery();

        } else {
            return user()->shipping_rules()->select('marketplace_shippings.*')->newQuery();

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
            'id' => ['visible' => true],
            'priority' => ['title' => trans('Marketplace::attributes.shipping.priority')],
            'name' => ['title' => trans('Marketplace::attributes.shipping.name')],
            'exclusive' => ['title' => trans('Marketplace::attributes.shipping.exclusive')],
            'shipping_method' => ['title' => trans('Marketplace::attributes.shipping.shipping_method')],
            'country' => ['title' => trans('Marketplace::attributes.shipping.country')],
            'rate' => ['title' => trans('Marketplace::attributes.shipping.rate')],
            'min_order_total' => ['title' => trans('Marketplace::attributes.shipping.min_order_total')],

        ];
        $columns = \Store::getStoreColumns($columns);
        return $columns;
    }

    protected function getOptions()
    {
        return ['has_action' => true];
    }

    public function getFilters()
    {

        $filters = [];
        $filters = \Store::getStoreFilters($filters);
        return $filters;
    }
}
