<?php

namespace Corals\Modules\Marketplace\Widgets;

use \Corals\Modules\Marketplace\Models\Coupon;

class CouponsWidget
{

    function __construct()
    {
    }

    function run($args)
    {

        $coupons = Coupon::count();
        return ' <!-- small box -->
                <div class="card">
                <div class="small-box bg-aqua card-body">
                    <div class="inner">
                        <h3>' . $coupons . '</h3>
                        <p>' . trans('Marketplace::labels.widget.coupon') . '</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-gift"></i>
                    </div>
                    <a href="' . url('marketplace/coupons') . '" class="small-box-footer">
                        ' . trans('Corals::labels.more_info') . '
                    </a>
                </div>
                </div>';
    }

}
