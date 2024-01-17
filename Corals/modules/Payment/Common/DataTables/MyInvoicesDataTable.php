<?php

namespace Corals\Modules\Payment\Common\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Payment\Common\Transformers\InvoiceTransformer;
use Yajra\DataTables\EloquentDataTable;

class MyInvoicesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('payment_common.models.invoice.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new InvoiceTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Invoice $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Invoice $model)
    {
        return $model->myInvoices()->newQuery();
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
            'code' => ['title' => trans('Payment::attributes.invoice.code')],
            'invoicable_type' => ['title' => trans('Payment::attributes.invoice.invoicable_type')],
            'invoicable_id' => ['title' => trans('Payment::attributes.invoice.invoicable_id')],
            'total' => ['title' => trans('Payment::attributes.invoice.total')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'description' => ['title' => trans('Payment::attributes.invoice.description')],
//            'public_link_copy' => [
//                'title' => trans('Payment::labels.invoice.public_link'),
//                'orderable' => false,
//                'searchable' => false
//            ],
            'due_date' => ['title' => trans('Payment::attributes.invoice.due_date')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
        ];
    }
}
