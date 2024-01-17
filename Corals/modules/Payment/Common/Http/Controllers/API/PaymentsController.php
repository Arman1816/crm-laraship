<?php

namespace Corals\Modules\Payment\Common\Http\Controllers\API;


use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Payment\Common\Services\PaymentService;
use Corals\Modules\Payment\Common\Models\TaxClass;
use Illuminate\Http\Request;

class PaymentsController extends APIBaseController
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;

        parent::__construct();
    }

    public function getPaymentSettings(Request $request)
    {
        try {
            $settings = $this->paymentService->getPaymentSettings();

            return apiResponse($settings);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function calculateTaxClassTaxes(Request $request)
    {
        $this->validate($request, [
            'tax_classes_ids' => 'required|array',
            'state' => '',
            'country' => '',
            'zip' => ''
        ]);

        try {
            $taxClassesIds = $request->get('tax_classes_ids');

            $address = $request->only(['state', 'country', 'zip']);

            $taxClasses = TaxClass::query()->whereIn('id', $taxClassesIds)->get();

            $taxes = \Payments::calculateTaxClassTaxes($taxClasses, $address);

            return apiResponse(apiPluck($taxes, 'tax_id', 'tax'));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
