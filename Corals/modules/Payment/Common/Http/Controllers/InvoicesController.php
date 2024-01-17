<?php

namespace Corals\Modules\Payment\Common\Http\Controllers;

use Carbon\Carbon;
use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Payment\Common\DataTables\InvoicesDataTable;
use Corals\Modules\Payment\Common\DataTables\MyInvoicesDataTable;
use Corals\Modules\Payment\Common\Http\Requests\InvoiceRequest;
use Corals\Modules\Payment\Common\Models\Invoice;
use Illuminate\Http\Request;

class InvoicesController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('payment_common.models.invoice.resource_url');
        $this->title = 'Payment::module.invoice.title';
        $this->title_singular = 'Payment::module.invoice.title_singular';

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, [
            'publicInvoice'
        ]);

        parent::__construct();
    }

    /**
     * @param InvoiceRequest $request
     * @param InvoicesDataTable $dataTable
     * @return mixed
     */
    public function index(InvoiceRequest $request, InvoicesDataTable $dataTable)
    {
        $this->setViewSharedData([
            'hideCreate' => true
        ]);
        return $dataTable->render('Payment::invoices.index');
    }

    /**
     * @param InvoiceRequest $request
     * @param Invoice $invoice
     * @return $this
     */
    public function edit(InvoiceRequest $request, Invoice $invoice)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $invoice->code])
        ]);

        $invoice->due_date = Carbon::parse($invoice->due_date)->toDateString();
        $invoice->invoice_date = Carbon::parse($invoice->invoice_date)->toDateString();
        $invoicable = $invoice->invoicable;

        return view('Payment::invoices.create_edit')->with(compact('invoice', 'invoicable'));
    }

    /**
     * @param InvoiceRequest $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        try {
            $data = $request->except('invoicable_id', 'invoicable_hashed_id', 'invoicable', 'invoicable_resource_url');
            $invoice->update($data);
            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Invoice::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param InvoiceRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function store(InvoiceRequest $request)
    {
        try {
            $data = $request->except('invoicable_resource_url', 'invoicable_hashed_id');
            Invoice::create($data);
            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Invoice::class, 'create');
        }
        if ($request->input('invoicable_resource_url')) {
            return redirectTo(url($request->input('invoicable_resource_url') . '/' . $request->input('invoicable_hashed_id')));
        } else {
            return redirectTo($this->resource_url);
        }
    }

    public function bulkAction(BulkRequest $request)
    {
        try {
            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);

            switch ($action) {
                case 'paid' :
                    foreach ($selection as $selection_id) {
                        $invoice = Invoice::findByHash($selection_id);
                        if (user()->can('Payment::invoices.update')) {
                            $invoice->update([
                                'status' => 'paid'
                            ]);
                            $invoice->save();
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

                case 'pending' :
                    foreach ($selection as $selection_id) {
                        $invoice = Invoice::findByHash($selection_id);
                        if (user()->can('Payment::invoices.update')) {
                            $invoice->update([
                                'status' => 'pending'
                            ]);
                            $invoice->save();
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
                case 'failed' :
                    foreach ($selection as $selection_id) {
                        $invoice = Invoice::findByHash($selection_id);
                        if (user()->can('Payment::invoices.update')) {
                            $invoice->update([
                                'status' => 'failed'
                            ]);
                            $invoice->save();
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
            log_exception($exception, Invoice::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


    public function myInvoices(Request $request, MyInvoicesDataTable $dataTable)
    {
        return $dataTable->renderAjaxAndActions();
    }

    public function show(InvoiceRequest $request, Invoice $invoice)
    {
        $gatewayPaymentDetails = $invoice->getInvoicePaymentDetails();

        return view('Payment::invoices.show', compact('invoice', 'gatewayPaymentDetails'));
    }

    /**
     * @param InvoiceRequest $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function download(InvoiceRequest $request, Invoice $invoice)
    {
        $pdf = \PDF::loadView('Payment::invoices.invoice', ['invoice' => $invoice, 'PDF' => true]);

        $fileName = $invoice->getPdfFileName();

        return $pdf->download($fileName);
    }

    /**
     * @param Request $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvoice(Request $request, Invoice $invoice)
    {
        if (!user()->can('sendInvoice', $invoice)) {
            abort(403);
        }

        try {
            event('notifications.invoice.send_invoice', ['invoice' => $invoice]);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.sent', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Invoice::class, 'sendInvoice');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicInvoice(Invoice $invoice)
    {
        return view('Payment::invoices.invoice', [
            'invoice' => $invoice,
            'PDF' => true
        ]);
    }
}
