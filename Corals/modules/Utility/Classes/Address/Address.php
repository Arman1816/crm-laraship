<?php

namespace Corals\Modules\Utility\Classes\Address;


use Corals\Modules\Utility\Models\Address\Location;

class Address
{
    /**
     * @param null $module
     * @param false $objects
     * @param string $status
     * @param string $orderBy
     * @param null $type
     * @param null $parent_id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getLocationsList($module = null, $objects = false, $status = 'active', $orderBy = 'name ASC', $type = null, $parent_id = null)
    {
        $locations = Location::query();

        if ($module) {
            $locations->where('module', $module);
        }

        if ($status) {
            $locations->where('status', $status);
        }

        if ($type) {
            $locations->where('type', $type);
        }

        if ($parent_id) {
            $locations->where('parent_id', $parent_id);
        }

        $locations = $locations->orderByRaw($orderBy);


        if ($objects) {
            return $locations->get();
        } else {
            return $locations->pluck('name', 'id');
        }
    }

    public function getLocationsCount($module = null, $status = null)
    {
        $locationsCount = Location::query();

        if ($module) {
            $locationsCount = $locationsCount->where('module', $module);
        }
        if ($status) {
            $locationsCount = $locationsCount->where('status', $status);
        }
        return $locationsCount->count();
    }

}
