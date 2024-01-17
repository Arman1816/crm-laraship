<?php

namespace Corals\Modules\Payment\Common\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Payment\Classes\Payments;
use Corals\Modules\Payment\Common\Models\Invoice;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class InvoicePaymentController extends BaseController
{

    public function __construct()
    {
        $this->corals_middleware = [];

        parent::__construct();
    }

    /**
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function publicInvoicePayment(Invoice $invoice)
    {
        $this->setViewSharedData(['title' => $invoice->code]);

        $available_gateways = \Corals\Modules\Payment\Facades\Payments::getAvailableGateways('support_ecommerce', 'offline_management');

        $gateway = '';

        $urlPrefix = '';

        return view('Payment::invoices.public_invoice_payment',
            compact('invoice', 'available_gateways', 'gateway', 'urlPrefix'));
    }

    /**
     * @param Request $request
     * @param $gateway
     * @param Invoice $invoice
     * @return mixed
     */
    public function gatewayPaymentToken(Request $request, $gateway, Invoice $invoice)
    {
        $params = $request->all();

        try {
            $invoicePayment = new Payments($gateway);

            return $invoicePayment->createPaymentToken($invoice, $params);
        } catch (\Exception $exception) {
            log_exception($exception, 'InvoicePaymentController', 'gatewayPaymentToken');
        }
    }


    /**
     * @param Request $request
     * @param $gateway
     * @return false|string
     */
    public function gatewayCheckPaymentToken(Request $request, $gateway)
    {
        $params = $request->all();

        try {
            $invoicePayment = new Payments($gateway);

            return $invoicePayment->checkPaymentToken($params);
        } catch (\Exception $exception) {
            log_exception($exception, 'Invoice', 'gatewayCheckPaymentToken');
            return json_encode(['status' => 'error', 'error' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param $gatewayName
     * @param $invoice
     * @return Factory|View
     * @throws \Exception
     */
    public function getGatewayPayment(Request $request, $gatewayName, Invoice $invoice)
    {
        $invoicePayment = new Payments($gatewayName);

        return $invoicePayment->getPaymentView($invoice);
    }

    /**
     * @param Request $request
     * @param Invoice $invoice
     * @return \Illuminate\Foundation\Application|JsonResponse|mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function doPay(Request $request, Invoice $invoice)
    {
        $this->validate($request, [
            'gateway' => 'required',
            'checkoutToken' => 'required'
        ]);

        if (in_array($invoice->status, ['paid'])) {
            abort(403, trans("Payment::exception.invoice.invoice_already_paid", ['invoice' => $invoice->code]));
        }

        try {
            $gateway = $request->get('gateway');

            $invoicePayment = new Payments($gateway);

            $invoicePayment->doPayment($request, $invoice);

            return redirectTo(
                URL::signedRoute('successPublicInvoicePayment', ['invoice' => $invoice->hashed_id])
            );
        } catch (\Exception $exception) {
            $message = [
                'level' => 'error',
                'message' => $exception->getMessage()
            ];

            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }

    /**
     * @param Invoice $invoice
     * @return Factory|\Illuminate\Contracts\View\View
     */
    public function success(Invoice $invoice)
    {
        $this->setViewSharedData(['title' => $invoice->code]);

        $gatewayPaymentDetails = $invoice->getInvoicePaymentDetails();

        return view('Payment::invoices.public_thanks_page')
            ->with(compact('invoice', 'gatewayPaymentDetails'));
    }

    /**
     * @param Request $request
     * @param Invoice $invoice
     * @return mixed
     */
    public function download(Request $request, Invoice $invoice)
    {
        $pdf = \PDF::loadView('Payment::invoices.invoice', ['invoice' => $invoice, 'PDF' => true]);

        $fileName = $invoice->getPdfFileName();

        return $pdf->download($fileName);
    }


}
