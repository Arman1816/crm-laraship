<?php

namespace Corals\Modules\Utility\DataTables\SEO;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Utility\Models\SEO\SEOItem;
use Corals\Modules\Utility\Transformers\SEO\SEOItemTransformer;
use Yajra\DataTables\EloquentDataTable;

class SEOItemsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('utility.models.seo_item.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new SEOItemTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param SEOItem $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(SEOItem $model)
    {
        return $model->newQuery();
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
            'slug' => ['title' => trans('Utility::attributes.seo_item.slug')],
            'route' => ['title' => trans('Utility::attributes.seo_item.route')],
            'title' => ['title' => trans('Utility::attributes.seo_item.title')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }
}
