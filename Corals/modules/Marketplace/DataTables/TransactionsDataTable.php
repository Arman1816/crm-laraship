<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\Modules\Payment\Common\DataTables\TransactionsDataTable as BaseTransactionsDataTable;


class TransactionsDataTable extends BaseTransactionsDataTable
{

    public function query(Transaction $model)
    {

        if (\Store::isStoreAdmin()) {
            return $model->newQuery();

        } else {
            return user()->transactions()->select('payment_transactions.*')->newQuery()->latest();

        }
    }

}
