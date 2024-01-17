<?php


namespace Corals\Modules\Payment\Common\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Payment\Common\DataTables\CurrenciesDataTable;
use Corals\Modules\Payment\Common\Http\Requests\CurrencyRequest;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\Modules\Payment\Common\Models\Currency;


class CurrenciesController extends BaseController
{

    public function __construct()
    {

        $this->setViewSharedData(['hideCreate' => true]);

        $this->resource_url = config('payment_common.models.currency.resource_url');
        $this->title = 'Payment::module.currency.title';
        $this->title_singular = 'Payment::module.currency.title_singular';


        parent::__construct();
    }

    public function index(CurrencyRequest $request, CurrenciesDataTable $dataTable)
    {
        return $dataTable->render('Payment::currencies.index');
    }


    public function edit(CurrencyRequest $request, Currency $currency)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $currency->code])]);

        return view('Payment::currencies.create_edit')->with(compact('currency'));
    }


    public function update(CurrencyRequest $request, Currency $currency)
    {
        try {
            $data = $request->all();

            $data['active'] = \Arr::get($data, 'active', 0);

            $currency->update($data);

            $currencyClass = app('currency');

            $currencyClass->clearCache();
            $currencyClass->getCurrencies();

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, \Currency::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    public function bulkAction(BulkRequest $request)
    {
        try {

            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);

            switch ($action) {
                case 'active' :
                    foreach ($selection as $selection_id) {
                        $currency = Currency::findByHash($selection_id);
                        if (user()->can('Payment::currency.update')) {
                            $currency->update([
                                'active' => true
                            ]);
                            $currency->save();
                            $message = ['level' => 'success', 'message' => trans('Payment::attributes.update_status', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('Payment::attributes.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;

                case 'inactive' :
                    foreach ($selection as $selection_id) {
                        $currency = Currency::findByHash($selection_id);
                        if (user()->can('Payment::invoices.update')) {
                            $currency->update([
                                'active' => false
                            ]);
                            $currency->save();
                            $message = ['level' => 'success', 'message' => trans('Payment::attributes.update_status', ['item' => $this->title_singular])];
                        } else {
                            $message = ['level' => 'error', 'message' => trans('Payment::attributes.no_permission', ['item' => $this->title_singular])];
                        }
                    }
                    break;
            }
        } catch (\Exception $exception) {
            log_exception($exception, \Currency::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }


    public function destroy(CurrencyRequest $request, Currency $currency)
    {
        try {
            $default_currency = config('currency.default');

            if (strtoupper($currency->code) == strtoupper($default_currency)) {
                throw new \Exception(trans('Payment::exceptions.currency.delete_default'));
            }

            $currency->delete();

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, \Currency::class, 'destroy');

            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
