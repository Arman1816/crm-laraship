<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Payment\Common\Models\WebhookCall;

class WebhookCallTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('payment_common.models.webhook_call.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param WebhookCall $webhookCall
     * @return array
     * @throws \Throwable
     */
    public function transform(WebhookCall $webhookCall)
    {
        $transformedArray = [
            'id' => $webhookCall->id,
            'checkbox' => $this->generateCheckboxElement($webhookCall),
            'event_name' => $webhookCall->event_name,
            'payload' => generatePopover("'" . $webhookCall->getRawOriginal('payload') . "'"),
            'exception' => $webhookCall->exception ? generatePopover("'" . $webhookCall->getRawOriginal('exception') . "'") : '-',
            'gateway' => $webhookCall->gateway,
            'processed' => $webhookCall->processed ? '<i class="fa fa-check text-success"></i>' : '-',
            'created_at' => format_date($webhookCall->created_at),
            'updated_at' => format_date($webhookCall->updated_at),
            'action' => $this->actions($webhookCall)
        ];

        return parent::transformResponse($transformedArray);
    }
}
