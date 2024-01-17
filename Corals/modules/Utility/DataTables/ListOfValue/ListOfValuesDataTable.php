<?php

namespace Corals\Modules\Utility\DataTables\ListOfValue;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Utility\Facades\ListOfValue\ListOfValues;
use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Corals\Modules\Utility\Transformers\ListOfValue\ListOfValueTransformer;
use Yajra\DataTables\EloquentDataTable;

class ListOfValuesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('utility.models.listOfValue.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new ListOfValueTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param ListOfValue $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(ListOfValue $model)
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
            'code' => ['title' => trans('Utility::attributes.listOfValue.code')],
            'value' => ['title' => trans('Utility::attributes.listOfValue.value')],
            'label' => ['title' => trans('Utility::attributes.listOfValue.label')],
            'parent' => [
                'title' => trans('Utility::attributes.listOfValue.parent'),
                'orderable' => false,
                'searchable' => false
            ],
            'module' => ['title' => trans('Utility::attributes.listOfValue.module')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'hidden' => ['title' => trans('Utility::attributes.listOfValue.hidden')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'code' => [
                'title' => trans('Utility::attributes.listOfValue.code'),
                'class' => 'col-md-3',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'label' => [
                'title' => trans('Utility::attributes.listOfValue.label'),
                'class' => 'col-md-3',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'value' => [
                'title' => trans('Utility::attributes.listOfValue.value'),
                'class' => 'col-md-3',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'parent_id' => [
                'title' => trans('Utility::attributes.listOfValue.parent'),
                'class' => 'col-md-2',
                'type' => 'select',
                'options' => ListOfValues::getParents(),
                'active' => true
            ],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => [
                'title' => trans('Corals::labels.delete'),
                'permission' => 'Utility::listOfValue.delete',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'active' => [
                'title' => '<i class="fa fa-check-circle"></i> ' . trans('Corals::attributes.status_options.active'),
                'permission' => 'Utility::listOfValue.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'inActive' => [
                'title' => '<i class="fa fa-check-circle-o"></i> ' . trans('Corals::attributes.status_options.inactive'),
                'permission' => 'Utility::listOfValue.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ]
        ];
    }

    protected function getOptions()
    {
        $url = url(config('utility.models.listOfValue.resource_url'));
        return ['resource_url' => $url];
    }
}
