<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Marketplace\Transformers\TagTransformer;
use Yajra\DataTables\EloquentDataTable;

class TagsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.tag.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new TagTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Tag $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Tag $model)
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
            'name' => ['title' => trans('Marketplace::attributes.tag.name')],
            'slug' => ['title' => trans('Marketplace::attributes.tag.slug')],
            'products_count' => ['title' => trans('Marketplace::attributes.tag.products_count'), 'searchable' => false],
            'status' => ['title' => trans('Corals::attributes.status')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'Marketplace::tag.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'active' => ['title' => '<i class="fa fa-check-circle"></i> ' . trans('Marketplace::status.store.active'), 'permission' => 'Marketplace::tag.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'inActive' => ['title' => '<i class="fa fa-check-circle-o"></i> ' . trans('Marketplace::status.store.inactive'), 'permission' => 'Marketplace::tag.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('marketplace.models.tag.resource_url'));
        return ['resource_url' => $url];
    }
}
