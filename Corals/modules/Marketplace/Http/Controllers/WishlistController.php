<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Utility\DataTables\Wishlist\Scopes\MyWishlistScope;
use Corals\Modules\Utility\DataTables\Wishlist\Scopes\WishlistTypeScope;
use Corals\Modules\Utility\DataTables\Wishlist\WishlistDataTable;
use Corals\Modules\Utility\Http\Controllers\Wishlist\WishlistBaseController;
use Illuminate\Http\Request;

class WishlistController extends WishlistBaseController
{
    protected function setCommonVariables()
    {
        $this->wishlistableClass = Product::class;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function myWishlist(Request $request)
    {
        if (!user()->hasPermissionTo('Utility::my_wishlist.access')) {
            abort(403);
        }

        $this->setViewSharedData([
            'title' => 'Marketplace::module.wishlist.title',
            'title_singular' => 'Marketplace::module.wishlist.title_singular'
        ]);

        $dataTable = new WishlistDataTable();

        $dataTable->setResourceUrl(config('marketplace.models.wishlist.resource_url') . '/my');

        $dataTable->addScope(new MyWishlistScope(user()));
        $dataTable->addScope(new WishlistTypeScope($this->wishlistableClass));

        return $dataTable->render('Marketplace::wishlist.index');
    }
}
