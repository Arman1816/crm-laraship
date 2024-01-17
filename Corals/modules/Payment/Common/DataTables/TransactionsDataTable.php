<?php

namespace Corals\Modules\Payment\Common\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\Modules\Payment\Common\Transformers\TransactionTransformer;
use Yajra\DataTables\EloquentDataTable;

class TransactionsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('payment_common.models.transaction.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new TransactionTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Transaction $model)
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
            'code' => ['title' => trans('Payment::attributes.transaction.code')],
            'invoice' => [
                'title' => trans('Payment::attributes.transaction.invoice'),
                'searchable' => false,
                'orderable' => false
            ],
            'type' => [
                'title' => trans('Payment::attributes.transaction.type'),
                'searchable' => false,
                'orderable' => false
            ],
            'status' => ['title' => trans('Payment::attributes.transaction.status')],
            'source' => [
                'title' => trans('Payment::attributes.transaction.source'),
                'searchable' => false,
                'orderable' => false
            ],
            'amount' => ['title' => trans('Payment::attributes.transaction.amount')],
            'paid_amount' => ['title' => trans('Payment::attributes.transaction.paid_amount')],
            'notes' => ['title' => trans('Payment::attributes.transaction.notes')],
            'reference' => ['title' => trans('Payment::attributes.transaction.reference')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')]
        ];
    }

    protected function getBulkActions()
    {
        return [
            'pending' => ['title' => '<i class="fa fa-clock-o" aria-hidden="true"></i> ' . trans('Payment::status.transaction.pending'), 'permission' => 'Payment::transaction.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'completed' => ['title' => '<i class="fa fa-check-circle" aria-hidden="true"></i> ' . trans('Payment::status.transaction.completed'), 'permission' => 'Payment::transaction.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'cancelled' => ['title' => '<i class="fa fa-close" aria-hidden="true"></i> ' . trans('Payment::status.transaction.cancelled'), 'permission' => 'Payment::transaction.update', 'confirmation' => trans('Corals::labels.confirmation.title')],

        ];
    }

    protected function getOptions()
    {
        $url = url(config('payment_common.models.transaction.resource_url'));
        return ['resource_url' => $url];
    }
}
