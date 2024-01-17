<?php

namespace Corals\Modules\Marketplace\Widgets;

use \Corals\Modules\Marketplace\Models\Order;

class OrdersWidget
{

    function __construct()
    {
    }

    function run($args)
    {

        $orders = Order::count();
        return ' <!-- small box -->
                <div class="card">
                <div class="small-box bg-yellow card-body">
                    <div class="inner">
                        <h3>' . $orders . '</h3>
                        <p>' . trans('Marketplace::labels.widget.orders') . '</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="' . url('marketplace/orders') . '" class="small-box-footer">
                       ' . trans('Corals::labels.more_info') . '
                    </a>
                </div>
                </div>';
    }

}
