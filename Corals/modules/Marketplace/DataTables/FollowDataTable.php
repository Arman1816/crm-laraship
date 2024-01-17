<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Transformers\FollowTransformer;
use Corals\Modules\Utility\Models\Wishlist\Wishlist;
use Corals\Modules\Utility\Transformers\Wishlist\WishlistTransformer;
use Yajra\DataTables\EloquentDataTable;

class FollowDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new FollowTransformer());
    }

    /**
     * @param Wishlist $model
     * @return mixed
     */
    public function query(Wishlist $model)
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
            'store_logo' => ['title' =>  '', "searchable" => false, 'orderable' => false],
            'store_name' => ['title' => trans('Marketplace::module.store.title_singular'), "searchable" => true, 'orderable' => true],
            'created_at' => ['title' => trans('Corals::attributes.created_at')]
        ];
    }

    protected function getOptions()
    {
        return ['has_action' => true];
    }
}
