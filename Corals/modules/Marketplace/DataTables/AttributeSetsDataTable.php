<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Models\AttributeSet;
use Corals\Modules\Marketplace\Transformers\AttributeSetTransformer;
use Yajra\DataTables\EloquentDataTable;

class AttributeSetsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.attribute_set.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new AttributeSetTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param AttributeSet $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(AttributeSet $model)
    {
        $query = $model->newQuery();

        if (!Store::isStoreAdmin()) {
            $store = Store::getVendorStore();
            $query->where('store_id', $store->id)->orWhereNull('store_id');
        }

        return $query;    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns =  [
            'id' => ['visible' => false],
            'code' => ['title' => trans('Marketplace::attributes.attribute_set.code')],
            'name' => ['title' => trans('Marketplace::attributes.attribute_set.name')],
            'is_default' => ['title' => trans('Marketplace::attributes.attribute_set.is_default')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
        $columns = \Store::getStoreColumns($columns);
        return  $columns;
    }
}
