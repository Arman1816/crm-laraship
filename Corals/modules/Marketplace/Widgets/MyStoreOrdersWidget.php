<?php

namespace Corals\Modules\Marketplace\Widgets;

class MyStoreOrdersWidget
{

    function __construct()
    {
    }

    function run($args)
    {

        $orders = user()->storeOrders()->count();
        return ' <!-- small box -->
                <div class="card">
                <div class="small-box bg-yellow card-body">
                    <div class="inner">
                        <h3>' . $orders . '</h3>
                        <p>' . trans('Marketplace::labels.widget.my_store_orders') . '</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="' . url('marketplace/orders/store') . '" class="small-box-footer">
                       ' . trans('Corals::labels.more_info') . '
                    </a>
                </div>
                </div>';
    }

}
