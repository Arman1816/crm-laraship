<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Carbon\Carbon;
use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Marketplace\DataTables\TransactionsDataTable;
use Corals\Modules\Marketplace\Http\Requests\withdrawalRequest;
use Corals\Modules\Payment\Common\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('marketplace.models.transaction.resource_url');

        $this->corals_middleware_except = [''];
        parent::__construct();

    }


    public function index(Request $request, TransactionsDataTable $dataTable)
    {

        $this->setViewSharedData([
            'title' => trans('Payment::module.transaction.title'),
            'hide_sidebar' => false,
            'hideCreate' => true

        ]);

        $transactionSummary = \Store::getTransactionsSummary();

        return $dataTable->render('Marketplace::transactions.index', $transactionSummary);
    }

    public function withdraw(Request $request)
    {
        if (!user()->hasPermissionTo('Marketplace::transaction.withdraw')) {
            abort(403);
        }

        $transaction = new Transaction();

        $this->setViewSharedData(['title_singular' => trans('Marketplace::labels.transactions.withdraw')]);
        $transactionSummary = \Store::getTransactionsSummary();
        $store = \Store::getVendorStore();


        $transactionSummary['transaction'] = $transaction;
        $transactionSummary['store'] = $store;


        return view('Marketplace::transactions.withdraw')->with($transactionSummary);
    }

    public function requestWithdrawal(withdrawalRequest $request)
    {
        if (!user()->hasPermissionTo('Marketplace::transaction.withdraw')) {
            abort(403);
        }


        try {

            $transaction = user()->transactions()->create([
                'amount' => -1 * $request->input('amount'),
                'transaction_date' => Carbon::now(),
                'type' => 'withdrawal',
                'notes' => $request->input('notes'),
                'status' => 'pending',
            ]);

            event('notifications.marketplace.withdrawal.requested', ['user' => user(), 'transaction' => $transaction]);

            flash(trans('Marketplace::labels.transactions.withdraw_request_success'))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Transaction::class, 'withdraw');
        }

        return redirectTo($this->resource_url);

    }


}
