<?php

namespace Corals\Modules\Marketplace\Widgets;


class MyStoreProductsWidget
{

    function __construct()
    {
    }

    function run($args)
    {

        $products = user()->products()->count();
        return ' <!-- small box -->
                <div class="card">
                <div class="small-box bg-green card-body">
                    <div class="inner">
                        <h3>' . $products . '</h3>
                        <p>' . trans('Marketplace::labels.widget.my_store_products') . '</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-product-hunt"></i>
                    </div>
                    <a href="' . url('marketplace/products') . '" class="small-box-footer">
                        ' . trans('Corals::labels.more_info') . '
                    </a>
                </div>
                </div>';
    }

}
