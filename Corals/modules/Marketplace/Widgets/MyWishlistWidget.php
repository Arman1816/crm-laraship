<?php

namespace Corals\Modules\Marketplace\Widgets;

use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Utility\Classes\Wishlist\WishlistManager;

class MyWishlistWidget
{

    function __construct()
    {
    }

    function run($args)
    {

        $wishlist = new WishlistManager(new Product());

        $wishlists = $wishlist->getUserWishlist(true);

        return ' <!-- small box -->
         <div class="card">
                <div class="small-box bg-yellow card-body">
                    <div class="inner">
                        <h3>' . $wishlists . '</h3>
                        <p>' . trans('Marketplace::labels.widget.my_wishlist') . '</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-heart"></i>
                    </div>
                    <a href="' . url('marketplace/wishlist/my') . '" class="small-box-footer">
                       ' . trans('Corals::labels.more_info') . '
                    </a>
                </div>
                </div>';
    }

}
