<?php


namespace Corals\Modules\Payment\Common\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Payment\Common\Http\Requests\CurrencyRequest;
use Corals\Modules\Payment\Common\Services\CurrencyService;
use Corals\Modules\Payment\Common\Transformers\API\CurrencyPresenter;
use Corals\Modules\Payment\Common\DataTables\CurrenciesDataTable;
use Corals\Modules\Payment\Common\Models\Currency;
use Illuminate\Http\Request;


class CurrenciesController extends APIBaseController
{
    protected $currencyService;

    /**
     * CurrenciesController constructor.
     * @param CurrencyService $currencyService
     * @throws \Exception
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;

        $this->currencyService->setPresenter(new CurrencyPresenter());

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, ['getActiveCurrenciesList']);

        parent::__construct();
    }

    /**
     * @param CurrencyRequest $request
     * @param CurrenciesDataTable $dataTable
     * @return mixed
     */
    public function index(CurrencyRequest $request, CurrenciesDataTable $dataTable)
    {
        $currencies = $dataTable->query(new Currency());

        return $this->currencyService->index($currencies, $dataTable);
    }

    public function getActiveCurrenciesList(Request $request)
    {
        try {
            $activeCurrencies = apiPluck(\Payments::getActiveCurrenciesList(), 'code', 'label');

            return apiResponse($activeCurrencies);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
