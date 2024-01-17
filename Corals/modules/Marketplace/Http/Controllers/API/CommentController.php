<?php

namespace Corals\Modules\Marketplace\Http\Controllers\API;

use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Utility\Http\Controllers\API\Comment\CommentAPIBaseController;

class CommentController extends CommentAPIBaseController
{
    protected function setCommonVariables()
    {
        $this->commentableClass = Product::class;
    }
}
