<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

 use Corals\Modules\Marketplace\DataTables\FollowDataTable;
 use Corals\Modules\Marketplace\Models\Store;
 use Corals\Modules\Utility\DataTables\Wishlist\Scopes\MyWishlistScope;
 use Corals\Modules\Utility\DataTables\Wishlist\Scopes\WishlistTypeScope;
 use Corals\Modules\Utility\Http\Controllers\Wishlist\WishlistBaseController;
 use Illuminate\Http\Request;

 class FollowController extends WishlistBaseController
 {

     protected $requireLoginMessage = 'Marketplace::messages.store.follow.require_login';

     protected $addSuccessMessage = 'Marketplace::messages.store.follow.added';
     protected $deleteSuccessMessage = 'Marketplace::messages.store.follow.removed';

     protected function setCommonVariables()
     {
         $this->wishlistableClass = Store::class;
     }

     /**
      * @param Request $request
      * @return mixed
      */
     public function myFollowList(Request $request)
     {
         if (!user()->hasPermissionTo('Utility::my_wishlist.access')) {
             abort(403);
         }

         $this->setViewSharedData([
             'title' => 'Marketplace::module.follow.title',
             'title_singular' => 'Marketplace::module.follow.title_singular'
         ]);

         $dataTable = new FollowDataTable();

         $dataTable->setResourceUrl(config('marketplace.models.follow.resource_url') . '/my');

         $dataTable->addScope(new MyWishlistScope(user()));
         $dataTable->addScope(new WishlistTypeScope($this->wishlistableClass));

         return $dataTable->render('Marketplace::wishlist.index');
     }
}
