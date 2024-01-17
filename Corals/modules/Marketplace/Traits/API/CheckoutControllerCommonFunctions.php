<?php

namespace Corals\Modules\Marketplace\Traits\API;

use Illuminate\Http\Request;

trait CheckoutControllerCommonFunctions
{
    /**
     * @param Request $request
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCouponByCode(Request $request, $code)
    {
        try {
            $coupon = $this->checkoutService->getCouponByCode($request, $code);

            return apiResponse($coupon);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function getAvailableShippingRoles(Request $request, $countryCode, $store_id)
    {
        try {
            $coupon = $this->checkoutService->getAvailableShippingRoles($request, strtoupper($countryCode), $store_id);

            return apiResponse($coupon);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
