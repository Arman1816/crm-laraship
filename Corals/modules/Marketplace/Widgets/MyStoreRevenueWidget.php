<?php

namespace Corals\Modules\Marketplace\Widgets;

class MyStoreRevenueWidget
{

    function __construct()
    {
    }

    function run($args)
    {


        $transactions_total = user()->transactions()->completed()->where('type', 'order_revenue')->sum('amount');

        if (!$transactions_total) {
            $transactions_total = 0;
        }

        return ' <!-- small box -->
            <div class="card">
                <div class="small-box bg-red card-body">
                    <div class="inner">
                        <h3>' . \Payments::admin_currency($transactions_total) . '</h3>
                        <p>' . trans('Marketplace::labels.widget.my_store_sales') . '</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="' . url('transactions') . '" class="small-box-footer">
                        ' . trans('Corals::labels.more_info') . '
                    </a>
                </div>
            </div>';
    }

}
