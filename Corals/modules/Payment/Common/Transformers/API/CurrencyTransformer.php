<?php

namespace Corals\Modules\Payment\Common\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Payment\Common\Models\Currency;

class CurrencyTransformer extends APIBaseTransformer
{
    /**
     * @param Currency $currency
     * @return array
     * @throws \Throwable
     */
    public function transform(Currency $currency)
    {
        $transformedArray = [
            'id' => $currency->id,
            'name' => $currency->name,
            'code' => $currency->code,
            'symbol' => $currency->symbol,
            'format' => $currency->format,
            'exchange_rate' => $currency->exchange_rate,
            'created_at' => format_date($currency->created_at),
            'updated_at' => format_date($currency->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }

}
