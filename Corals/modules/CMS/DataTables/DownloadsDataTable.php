<?php


namespace Corals\Modules\CMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\CMS\Models\Download;
use Corals\Modules\CMS\Transformers\DownloadTransformer;
use Yajra\DataTables\EloquentDataTable;

class DownloadsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('cms.models.download.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new DownloadTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Download $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Download $model)
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
            'title' => ['title' => trans('CMS::attributes.content.title')],
            'files' => ['title' => trans('CMS::attributes.download.files')],
            'published' => ['title' => trans('CMS::attributes.content.published')],
            'published_at' => ['title' => trans('CMS::attributes.content.published_at')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }
}
