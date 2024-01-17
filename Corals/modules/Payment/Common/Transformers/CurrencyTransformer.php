<?php

namespace Corals\Modules\Payment\Common\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Payment\Common\Models\Currency;

class CurrencyTransformer extends BaseTransformer
{

    public function __construct($extras = [])
    {
        $this->resource_url = config('payment_common.models.currency.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Currency $currency
     * @return array
     * @throws \Throwable
     */
    public function transform(Currency $currency)
    {
        $transformedArray = [
            'id' => $currency->id,
            'checkbox' => $this->generateCheckboxElement($currency),
            'name' => $currency->name,
            'code' => $currency->code,
            'symbol' => $currency->symbol,
            'format' => $currency->format,
            'exchange_rate' => $currency->exchange_rate,
            'active' => formatStatusAsLabels($currency->active),
            'created_at' => format_date($currency->created_at),
            'updated_at' => format_date($currency->updated_at),
            'action' => $this->actions($currency)
        ];

        return parent::transformResponse($transformedArray);
    }

}
