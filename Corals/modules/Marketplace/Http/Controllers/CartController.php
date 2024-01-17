<?php

namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    /**
     * CartController constructor.
     */

    public function __construct()
    {
        $this->title = 'Marketplace::module.cart.title';
        $this->title_singular = 'Marketplace::module.cart.title';
        parent::__construct();
    }

    /**
     * @return mixed
     */
    private function canAccessCart()
    {
        if (!user()->hasPermissionTo('Marketplace::cart.access')) {
            abort(403);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->canAccessCart();

        return view('Marketplace::cart.show');
    }
}
