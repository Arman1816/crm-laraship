<?php

namespace Corals\Modules\Payment\Common\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Payment\Classes\Payments;
use Corals\Modules\Payment\Common\DataTables\TransactionsDataTable;
use Corals\Modules\Payment\Common\Http\Requests\TransactionRequest;
use Corals\Modules\Payment\Common\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('payment_common.models.transaction.resource_url');

        $this->corals_middleware_except = [];

        parent::__construct();
    }


    public function index(Request $request, TransactionsDataTable $dataTable)
    {
        $this->setViewSharedData([
            'title' => trans('Payment::module.transaction.title'),
            'hideCreate' => true
        ]);

        return $dataTable->render('Payment::transactions.index');
    }

    /**
     * @param Transaction $transaction
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Transaction $transaction)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $transaction->code])
        ]);

        return view('Payment::transactions.create_edit')->with(compact('transaction'));
    }

    /**
     * @param TransactionRequest $request
     * @param Transaction $transaction
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        try {
            $data = $request->all();
            $transaction->update($data);


            flash(trans('Corals::messages.success.updated', ['item' => $transaction->code]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Transaction::class, 'update');
        }

        return redirectTo($this->resource_url);
    }


    public function bulkAction(BulkRequest $request)
    {
        try {
            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);

            switch ($action) {
                case 'pending' :
                    foreach ($selection as $selection_id) {
                        $transaction = Transaction::findByHash($selection_id);
                        if (user()->can('Payment::transaction.update')) {
                            $transaction->update([
                                'status' => 'pending'
                            ]);
                            $transaction->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Payment::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Payment::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;

                case 'completed' :
                    foreach ($selection as $selection_id) {
                        $transaction = Transaction::findByHash($selection_id);
                        if (user()->can('Payment::transaction.update')) {
                            $transaction->update([
                                'status' => 'completed'
                            ]);
                            $transaction->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Payment::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Payment::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;
                case 'cancelled' :
                    foreach ($selection as $selection_id) {
                        $transaction = Transaction::findByHash($selection_id);
                        if (user()->can('Payment::transaction.update')) {
                            $transaction->update([
                                'status' => 'cancelled'
                            ]);
                            $transaction->save();
                            $message = [
                                'level' => 'success',
                                'message' => trans('Payment::attributes.update_status',
                                    ['item' => $this->title_singular])
                            ];
                        } else {
                            $message = [
                                'level' => 'error',
                                'message' => trans('Payment::attributes.no_permission',
                                    ['item' => $this->title_singular])
                            ];
                        }
                    }
                    break;
            }
        } catch (\Exception $exception) {
            log_exception($exception, Transaction::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    /**
     * @param TransactionRequest $request
     * @param Transaction $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TransactionRequest $request, Transaction $transaction)
    {
        try {
            $transaction->delete();

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Transaction::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    /**
     * @param TransactionRequest $request
     * @param Transaction $transaction
     */
    public function reversePayout(Request $request, Transaction $transaction)
    {
        $this->authorize('canReverseTransfer', $transaction);

        try {
            $gateway = $transaction->getProperty('gateway');

            $payments = new Payments($gateway);

            $payments->reverseTransfer($transaction);

            $message = [
                'level' => 'success',
                'message' => trans('Payment::messages.reverse_request_submitted')
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Transaction::class, 'reversePayout');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
