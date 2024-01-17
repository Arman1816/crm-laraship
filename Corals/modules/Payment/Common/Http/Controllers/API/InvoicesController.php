<?php


namespace Corals\Modules\Payment\Common\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Payment\Common\Services\InvoiceService;
use Corals\Modules\Payment\Common\Transformers\API\InvoicePresenter;
use Corals\Modules\Payment\Common\DataTables\InvoicesDataTable;
use Corals\Modules\Payment\Common\DataTables\MyInvoicesDataTable;
use Corals\Modules\Payment\Common\Http\Requests\InvoiceRequest;
use Corals\Modules\Payment\Common\Models\Invoice;
use Illuminate\Http\Request;


class InvoicesController extends APIBaseController
{
    protected $invoiceService;

    /**
     * InvoicesController constructor.
     * @param InvoiceService $invoiceService
     * @throws \Exception
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;

        $this->invoiceService->setPresenter(new InvoicePresenter());

        parent::__construct();
    }

    /**
     * @param InvoiceRequest $request
     * @param InvoicesDataTable $dataTable
     * @return mixed
     */
    public function index(InvoiceRequest $request, InvoicesDataTable $dataTable)
    {
        $invoices = $dataTable->query(new Invoice());

        return $this->invoiceService->index($invoices, $dataTable);
    }

    /**
     * @param Request $request
     * @param MyInvoicesDataTable $dataTable
     * @return mixed
     */
    public function myInvoices(Request $request, MyInvoicesDataTable $dataTable)
    {
        $invoices = $dataTable->query(new Invoice());

        return $this->invoiceService->index($invoices, $dataTable);
    }
}
