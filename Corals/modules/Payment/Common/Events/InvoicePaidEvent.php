<?php


namespace Corals\Modules\Payment\Common\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoicePaidEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoice;
    /**
     * @var array|mixed
     */
    public $payLoad;

    /**
     * InvoicePaidEvent constructor.
     * @param $invoice
     * @param array $payLoad
     */
    public function __construct($invoice, $payLoad = [])
    {
        $this->invoice = $invoice;
        $this->payLoad = $payLoad;
    }
}
