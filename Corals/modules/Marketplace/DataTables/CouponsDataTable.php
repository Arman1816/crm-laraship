<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Facades\Store;
use Corals\Modules\Marketplace\Models\Coupon;
use Corals\Modules\Marketplace\Transformers\CouponTransformer;
use Yajra\DataTables\EloquentDataTable;

class CouponsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.coupon.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new CouponTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Coupon $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Coupon $model)
    {
        $query = $model->newQuery();

        if (!Store::isStoreAdmin()) {
            $store = Store::getVendorStore();
            $query->where('store_id', $store->id);
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
        $columns = [
            'id' => ['visible' => false],
            'code' => ['title' => trans('Marketplace::attributes.coupon.code')],
            'value' => ['title' => trans('Marketplace::attributes.coupon.value')],
            'status' => ['title' => trans('Corals::attributes.status'), 'orderable' => false, 'searchable' => false],
            'type' => ['title' => trans('Marketplace::attributes.coupon.type')],
            'start' => ['title' => trans('Marketplace::attributes.coupon.start')],
            'expiry' => ['title' => trans('Marketplace::attributes.coupon.expiry')]
        ];

        $columns = \Store::getStoreColumns($columns);
        return $columns;
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'Marketplace::coupon.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('marketplace.models.coupon.resource_url'));
        return ['has_action' => true, 'resource_url' => $url];
    }

}
