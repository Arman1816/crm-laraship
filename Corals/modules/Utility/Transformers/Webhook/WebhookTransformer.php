<?php

namespace Corals\Modules\Utility\Transformers\Webhook;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Utility\Models\Webhook\Webhook;

class WebhookTransformer extends BaseTransformer
{
    public function __construct()
    {
        $this->resource_url = config('utility.models.webhook.resource_url');

        parent::__construct();
    }

    /**
     * @param Webhook $webhook
     * @return array
     * @throws \Throwable
     */
    public function transform(Webhook $webhook)
    {
        $statusText = trans('Utility::attributes.webhook.status_options.' . $webhook->status);

        $statusLevel = 'default';

        switch ($webhook->status) {
            case 'processed':
                $statusLevel = 'success';
                break;
            case 'partially_processed':
                $statusLevel = 'warning';
                break;
        }

        $transformedArray = [
            'id' => $webhook->id,
            'checkbox' => $this->generateCheckboxElement($webhook),
            'event_name' => $webhook->event_name,
            'payload' => generatePopover("'" . $webhook->getRawOriginal('payload') . "'"),
            'exception' => $webhook->exception ? generatePopover("'" . $webhook->getRawOriginal('exception') . "'") : '-',
            'properties' => $webhook->properties ? generatePopover(formatProperties($webhook->properties)) : '-',
            'status' => formatStatusAsLabels($statusText, ['level' => $statusLevel]),
            'store' => $webhook->store ? $webhook->store->name : '-',
            'created_by' => $webhook->created_by ? ($webhook->creator->full_name . '(' . $webhook->creator->email . ')') : '-',
            'created_at' => format_date_time($webhook->created_at),
            'updated_at' => format_date($webhook->updated_at),
            'action' => $this->actions($webhook)
        ];

        return parent::transformResponse($transformedArray);
    }
}
