<?php

namespace Corals\Modules\Payment\Common\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Payment\Common\Models\Invoice;
use Corals\Modules\Payment\Common\Transformers\InvoiceTransformer;
use Yajra\DataTables\EloquentDataTable;

class InvoicesDataTable extends BaseDataTable
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
        return $model->with('user')->newQuery();
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
            'is_sent' => ['title' => trans('Payment::attributes.invoice.is_sent')],
            'invoicable_type' => ['title' => trans('Payment::attributes.invoice.invoicable_type')],
            'invoicable_id' => ['title' => trans('Payment::attributes.invoice.invoicable_id')],
            'customer' => ['title' => trans('Payment::attributes.invoice.customer')],
            'total' => ['title' => trans('Payment::attributes.invoice.total')],
            'public_link_copy' => [
                'title' => trans('Payment::labels.invoice.public_link'),
                'orderable' => false,
                'searchable' => false
            ],
            'status' => ['title' => trans('Corals::attributes.status')],
            'description' => ['title' => trans('Payment::attributes.invoice.description')],
            'due_date' => ['title' => trans('Payment::attributes.invoice.due_date')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')]
        ];
    }

    protected function getBulkActions()
    {
        return [
            'paid' => [
                'title' => '<i class="fa fa-money" aria-hidden="true"></i> ' . trans('Payment::status.invoice.paid'),
                'permission' => 'Payment::invoices.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'pending' => [
                'title' => '<i class="fa fa-clock-o" aria-hidden="true"></i> ' . trans('Payment::status.invoice.pending'),
                'permission' => 'Payment::invoices.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ],
            'failed' => [
                'title' => '<i class="fa fa-ban" aria-hidden="true"></i>  ' . trans('Payment::status.invoice.failed'),
                'permission' => 'Payment::invoices.update',
                'confirmation' => trans('Corals::labels.confirmation.title')
            ]
        ];
    }

    protected function getOptions()
    {
        $url = url(config('payment_common.models.invoice.resource_url'));
        return ['resource_url' => $url];
    }
}
