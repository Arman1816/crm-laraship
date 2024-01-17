<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Models\Category;
use Corals\Modules\Marketplace\Transformers\CategoryTransformer;
use Yajra\DataTables\EloquentDataTable;

class CategoriesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.category.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new CategoryTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Category $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Category $model)
    {
        return $model->withCount('products');
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
            'name' => ['title' => trans('Marketplace::attributes.category.name')],
            'slug' => ['title' => trans('Marketplace::attributes.category.slug')],
            'parent_id' => ['title' => trans('Marketplace::attributes.category.parent_id')],
            'products_count' => ['title' => trans('Marketplace::attributes.category.products_count'), 'searchable' => false],
            'status' => ['title' => trans('Corals::attributes.status')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'name' => ['title' => trans('Marketplace::attributes.category.name'), 'class' => 'col-md-3', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'created_at' => ['title' => trans('Corals::attributes.created_at'), 'class' => 'col-md-2', 'type' => 'date', 'active' => true],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'Marketplace::category.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'active' => ['title' => '<i class="fa fa-check-circle"></i> ' . trans('Marketplace::status.store.active'), 'permission' => 'Marketplace::category.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'inActive' => ['title' => '<i class="fa fa-check-circle-o"></i> ' . trans('Marketplace::status.store.inactive'), 'permission' => 'Marketplace::category.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('marketplace.models.category.resource_url'));
        return ['resource_url' => $url];
    }
}
