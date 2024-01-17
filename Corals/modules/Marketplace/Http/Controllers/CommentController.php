<?php


namespace Corals\Modules\Marketplace\Http\Controllers;

use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Utility\Http\Controllers\Comment\CommentBaseController;

class CommentController extends CommentBaseController
{
    protected function setCommonVariables()
    {
        $this->commentableClass = Product::class;
    }
}
