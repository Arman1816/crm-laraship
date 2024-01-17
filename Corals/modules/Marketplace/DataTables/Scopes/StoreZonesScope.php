<?php

namespace Corals\Modules\Marketplace\DataTables\Scopes;


use Corals\Foundation\Contracts\CoralsScope;

class StoreZonesScope implements CoralsScope
{

    public function __construct()
    {
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query, $extras = [])
    {
        $query->where('advert_zones.key', 'like', 'store%');
    }
}
