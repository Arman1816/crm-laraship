<?php

namespace Corals\Modules\Utility\Services\Address;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Utility\Facades\Address\Address;
use Illuminate\Http\Request;

class LocationService extends BaseServiceClass
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getLocationTypeChildren(Request $request)
    {
        $values = array_values($request->all());

        $locationId = data_get($values, '0');
        $type = data_get($values, '1');

        return Address::getLocationsList($module = null, $objects = false, $status = 'active', $orderBy = 'name ASC', $type, $parent_id = $locationId);
    }
}
