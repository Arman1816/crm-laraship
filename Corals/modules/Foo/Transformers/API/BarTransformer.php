<?php

namespace Corals\Modules\Foo\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Foo\Models\Bar;

class BarTransformer extends APIBaseTransformer
{
    /**
     * @param Bar $bar
     * @return array
     * @throws \Throwable
     */
    public function transform(Bar $bar)
    {
        $transformedArray = [
            'id' => $bar->id,
            'name' => $bar->name,
            'created_at' => format_date($bar->created_at),
            'updated_at' => format_date($bar->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
