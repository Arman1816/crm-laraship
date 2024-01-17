<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Jobs\GenerateExcelForProducts;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Transformers\ProductTransformer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Yajra\DataTables\EloquentDataTable;

class ProductsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.product.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new ProductTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Product $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Product $model)
    {
        if (Store::isStoreAdmin()) {
            return $model->newQuery();
        } else {
            return user()->products()->select('marketplace_products.*')->newQuery();
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
            'image' => [
                'width' => '50px',
                'title' => trans('Marketplace::attributes.product.image'),
                'orderable' => false,
                'searchable' => false
            ],
            'name' => ['title' => trans('Marketplace::attributes.product.name')],
            'type' => ['title' => trans('Marketplace::attributes.product.type')],
            'system_price' => [
                'title' => trans('Marketplace::attributes.product.price'),
                'orderable' => false,
                'searchable' => false
            ],
            'shippable' => [
                'title' => trans('Marketplace::attributes.product.shippable'),
                'orderable' => false,
                'searchable' => false
            ],
            'categories' => [
                'title' => trans('Marketplace::attributes.product.categories'),
                'orderable' => false,
                'searchable' => false
            ],
            'status' => ['title' => trans('Corals::attributes.status')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
        $columns = \Store::getStoreColumns($columns);
        return $columns;
    }

    public function getFilters()
    {
        $filters = [
            'name' => [
                'title' => trans('Marketplace::attributes.product.title_name'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'description' => [
                'title' => trans('Marketplace::attributes.product.description'),
                'class' => 'col-md-3',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'brand.id' => [
                'title' => trans('Marketplace::attributes.product.brand'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => \Marketplace::getBrandsList(),
                'active' => true
            ],
            'status' => [
                'title' => trans('Marketplace::attributes.product.status_product'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => trans('Corals::attributes.status_options'),
                'active' => true
            ],
        ];
        $filters = \Store::getStoreFilters($filters);
        return $filters;
    }

    protected function getBulkActions()
    {
        return [
            'delete' => [
                'title' => trans('Corals::labels.delete'),
                'permission' => 'Marketplace::product.delete',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'active' => [
                'title' => '<i class="fa fa-check-circle"></i> ' . trans('Marketplace::status.store.active'),
                'permission' => 'Marketplace::product.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'inActive' => [
                'title' => '<i class="fa fa-check-circle-o"></i> ' . trans('Marketplace::status.store.inactive'),
                'permission' => 'Marketplace::product.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'deleted' => [
                'title' => '<i class="fa fa-window-close"></i> ' . trans('Marketplace::attributes.product.deleted'),
                'permission' => 'Marketplace::product.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'copy-products' => [
                'title' => trans('Marketplace::labels.product.clone_product'),
                'permission' => 'Marketplace::product.update',
                'confirmation' => trans('Corals::labels.confirmation.title'),

                'action' => 'modal-load',
                'href' => url('marketplace/products/get-bulk-copy-products-modal'),
                'modal-title' => trans('Marketplace::labels.product.clone_product')
            ]
        ];
    }

    protected function getOptions()
    {
        $url = url(config('marketplace.models.product.resource_url'));
        return ['resource_url' => $url];
    }

    protected function getBuilderParameters()
    {
        $parentParams = parent::getBuilderParameters();

        $idColumnIndex = array_search('id', array_keys($this->getColumns()));

        return array_merge($parentParams, ['order' => [[$idColumnIndex + 1, 'desc']]]);
    }

    /**
     * @param $download
     * @return ShouldQueue
     */
    protected function getCSVGeneratorJob($download)
    {
        $columns = $this->getExportColumnsFromBuilder();

        return new GenerateExcelForProducts(
            get_class($this),
            $this->scopes,
            $columns,
            $this->getTableId(),
            user(),
            $download
        );
    }
}
