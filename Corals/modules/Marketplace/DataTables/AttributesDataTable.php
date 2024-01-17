<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Transformers\AttributeTransformer;
use Yajra\DataTables\EloquentDataTable;

class AttributesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.attribute.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new AttributeTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Attribute $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Attribute $model)
    {
        $query = $model->newQuery();

        if (!Store::isStoreAdmin()) {
            $store = Store::getVendorStore();
            $query->where('store_id', $store->id)->orWhereNull('store_id');
        }

        return $query;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns =  [
            'id' => ['visible' => false],
            'label' => ['title' => trans('Marketplace::attributes.attributes.label')],
            'type' => ['title' => trans('Marketplace::attributes.attributes.type')],
            'required' => ['title' => trans('Marketplace::attributes.attributes.required')],
            'attribute_sets' => [
                'title' => trans('Marketplace::attributes.attribute_set.sets'),
                'orderable' => false,
                'searchable' => false
            ],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];

        $columns = \Store::getStoreColumns($columns);
        return  $columns;
    }
}
