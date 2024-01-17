<?php

namespace Corals\Modules\Utility\Transformers\API\Address;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Modules\Utility\Models\Address\Location;

class LocationTransformer extends APIBaseTransformer
{
    /**
     * @param Location $location
     * @return array
     * @throws \Throwable
     */
    public function transform(Location $location)
    {
        $transformedArray = [
            'id' => $location->id,
            'name' => $location->name,
            'address' => $location->address,
            'lat' => $location->lat,
            'long' => $location->long,
            'zip' => $location->zip,
            'city' => $location->city,
            'state' => $location->state,
            'country' => $location->country,
            'status' => $location->status,
            'created_at' => format_date($location->created_at),
            'updated_at' => format_date($location->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
