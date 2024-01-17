<?php


namespace Corals\Modules\Marketplace\DataTables\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SKUAttributesScope
{
    /**
     * @param Builder $query
     * @param $column
     * @param $value
     */
    public function apply(Builder $query, $column, $value): void
    {
        if (!$value) return;

        $aliasColumn = Str::replaceArray('-', ['_'], $column);
        $values = join(',', Arr::wrap($value));

        $whereRawValueColumn = "
                    CASE
                          WHEN join_attribute_$aliasColumn.type  in ('checkbox', 'text','date') THEN join_option_$aliasColumn.string_value
                          WHEN join_attribute_$aliasColumn.type in ('textarea') THEN join_option_$aliasColumn.text_value
                          WHEN join_attribute_$aliasColumn.type in ('number','select','multi_values','radio') THEN join_option_$aliasColumn.number_value
                    ELSE
    	                join_option_$aliasColumn.string_value
                    END in ($values)";
        

        $query->join("marketplace_sku_options as join_option_$aliasColumn", 'marketplace_sku.id', "join_option_$aliasColumn.sku_id")
            ->join("marketplace_attributes as join_attribute_$aliasColumn", function ($join) use ($column, $aliasColumn) {

                $join->on("join_option_$aliasColumn.attribute_id", "join_attribute_$aliasColumn.id")
                    ->where("join_attribute_$aliasColumn.code", $column);

            })->select('marketplace_sku.*')
            ->distinct('marketplace_sku.id')
            ->whereRaw($whereRawValueColumn);

    }

}