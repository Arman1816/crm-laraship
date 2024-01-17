<?php

namespace Corals\Modules\Utility\Services\ListOfValue;

use Corals\Foundation\Services\BaseServiceClass;
use Illuminate\Http\Request;

class ListOfValueService extends BaseServiceClass
{
    /**
     * @param Request $request
     * @param $additionalData
     */
    public function preStoreUpdate(Request $request, &$additionalData)
    {
        $properties = $request->get('properties', []);

        $formattedProperties = null;

        foreach ($properties ?? [] as $item) {
            $formattedProperties[$item['key']] = $item['value'];
        }

        $request->request->set('properties', $formattedProperties);
    }
}
