<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Facades\Marketplace;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Transformers\SKUTransformer;
use Yajra\DataTables\EloquentDataTable;

class SKUDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.sku.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new SKUTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param SKU $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(SKU $model)
    {
        $product = $this->request->route('product');

        if (!$product) {
            abort(404, 'Not Found!!');
        }

        return $model->newQuery()->where('marketplace_sku.product_id', $product->id);
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
            'image' => [
                'width' => '50px',
                'title' => trans('Marketplace::attributes.sku.image'),
                'orderable' => false,
                'searchable' => false
            ],
            'code' => ['title' => trans('Marketplace::attributes.sku.code')],
            'price' => [
                'title' => trans('Marketplace::attributes.sku.price'),
                'orderable' => false,
                'searchable' => false
            ],
            'inventory' => ['title' => trans('Marketplace::attributes.sku.inventory')],
            'dt_options' => [
                'title' => trans('Marketplace::attributes.sku.dt_options'),
                'orderable' => false,
                'searchable' => false
            ],
            'status' => ['title' => trans('Corals::attributes.status')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'code' => [
                'title' => trans('Marketplace::attributes.sku.code'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'status' => [
                'title' => trans('Corals::attributes.status'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => trans('Corals::attributes.status_options'),
                'active' => true
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getCustomRenderedFilters(): array
    {
        return Marketplace::renderSKUAttributesForFilters();
    }

    /**
     * @return array
     */
    protected function getBulkActions()
    {
        return [
            'updateVariations' => [
                'title' => '<i class="fa fa-pencil fa-fw"></i> ' . trans('Marketplace::labels.sku.update_variations'),
                'permission' => 'Marketplace::product.update',
                'confirmation' => '',
                'action' => 'modal-load',
                'href' => url('marketplace/products/sku/get-bulk-update-variations-modal'),
                'modal-title' => trans('Marketplace::labels.sku.update_variations')
            ],
            'delete' => [
                'title' => trans('Corals::labels.delete'),
                'permission' => 'Marketplace::product.delete',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
        ];
    }

    protected function getOptions()
    {
        return [
            'resource_url' => url('marketplace/products/sku')
        ];
    }
}
