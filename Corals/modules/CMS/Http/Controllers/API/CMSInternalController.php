<?php

namespace Corals\Modules\CMS\Http\Controllers\API;

use Corals\Modules\CMS\Services\CMSService;

class CMSInternalController extends CMSPublicController
{
    public function __construct(CMSService $CMSService)
    {
        $this->corals_middleware = ['auth:api'];

        $CMSService->internalState = true;

        parent::__construct($CMSService);
    }
}
