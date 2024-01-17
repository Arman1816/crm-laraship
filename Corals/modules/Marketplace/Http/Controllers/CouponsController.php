<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Marketplace\DataTables\CouponsDataTable;
use Corals\Modules\Marketplace\Http\Requests\CouponRequest;
use Corals\Modules\Marketplace\Models\Coupon;
use Corals\Modules\Marketplace\Services\CouponService;
use Illuminate\Validation\ValidationException;

class CouponsController extends BaseController
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;

        $this->resource_url = config('marketplace.models.coupon.resource_url');
        $this->title = 'Marketplace::module.coupon.title';
        $this->title_singular = 'Marketplace::module.coupon.title_singular';
        parent::__construct();
    }

    /**
     * @param CouponRequest $request
     * @param CouponsDataTable $dataTable
     * @return mixed
     */
    public function index(CouponRequest $request, CouponsDataTable $dataTable)
    {
        return $dataTable->render('Marketplace::coupons.index');
    }

    /**
     * @param CouponRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(CouponRequest $request)
    {
        $coupon = new Coupon();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('Marketplace::coupons.create_edit')->with(compact('coupon'));
    }

    /**
     * @param CouponRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function store(CouponRequest $request)
    {
        try {
            $this->couponService->store($request, Coupon::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Coupon::class, 'store');

            if ($exception instanceof ValidationException) {
                return response()->json(['message' => trans('validation.message'), 'errors' => $exception->validator->getMessageBag()], 422);
            }
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param CouponRequest $request
     * @param Coupon $coupon
     * @return Coupon
     */
    public function show(CouponRequest $request, Coupon $coupon)
    {
        return $coupon;
    }

    /**
     * @param CouponRequest $request
     * @param Coupon $coupon
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(CouponRequest $request, Coupon $coupon)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $coupon->code])]);

        return view('Marketplace::coupons.create_edit')->with(compact('coupon'));
    }

    /**
     * @param CouponRequest $request
     * @param Coupon $coupon
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        try {
            $this->couponService->update($request, $coupon);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Coupon::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    public function bulkAction(BulkRequest $request)
    {
        try {
            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);
            switch ($action) {
                case 'delete':
                    foreach ($selection as $selection_id) {
                        $coupon = Coupon::findByHash($selection_id);
                        $coupon_request = new CouponRequest;
                        $coupon_request->setMethod('DELETE');
                        $this->destroy($coupon_request, $coupon);
                    }
                    $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
                    break;
            }
        } catch (\Exception $exception) {
            log_exception($exception, Coupon::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    /**
     * @param CouponRequest $request
     * @param Coupon $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CouponRequest $request, Coupon $coupon)
    {
        try {
            $this->couponService->destroy($request, $coupon);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Coupon::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

}
