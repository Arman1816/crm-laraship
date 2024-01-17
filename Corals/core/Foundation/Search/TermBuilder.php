<?php

namespace Corals\Foundation\Search;

class TermBuilder
{

    public static function terms($search, $config)
    {
        $search = trim(preg_replace('/[\/+\-><()~*\"@.]+/', 'X', $search));

        $wildcards = $config['enable_wildcards'] ?? true;

        $terms = collect(preg_split('/[\s,]+/', $search));

        if ($wildcards === true || $wildcards === 'true') {
            $terms = $terms->reject(function ($part) {
                return empty(trim($part));
            })->map(function ($part) {
                return $part . '*';
            });
        }
        return $terms;
    }

}
