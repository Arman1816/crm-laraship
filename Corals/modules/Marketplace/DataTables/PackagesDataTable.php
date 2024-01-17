<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Models\Package;
use Corals\Modules\Marketplace\Transformers\PackageTransformer;
use Yajra\DataTables\EloquentDataTable;

class PackagesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.package.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new PackageTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Package $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Package $model)
    {
        if (Store::isStoreAdmin()) {
            return $model->newQuery();
        } else {
            return user()->shipping_packages()->select('marketplace_shipping_packages.*')->newQuery();
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
            'name' => ['title' => trans('Marketplace::attributes.package.name')],
            'dimension_template' => ['title' => trans('Marketplace::attributes.package.template')],
            'dimensions' => [
                'title' => trans('Marketplace::attributes.package.dimensions'),
                'searchable' => false,
                'orderable' => false
            ],
            'package_weight' => [
                'title' => trans('Marketplace::attributes.package.weight'),
                'searchable' => false,
                'orderable' => false
            ],
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
