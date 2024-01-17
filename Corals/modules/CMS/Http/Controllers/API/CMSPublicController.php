<?php

namespace Corals\Modules\CMS\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIPublicController;
use Corals\Modules\CMS\Services\CMSService;
use Illuminate\Http\Request;

class CMSPublicController extends APIPublicController
{
    protected $CMSService;

    public function __construct(CMSService $CMSService)
    {
        $this->CMSService = $CMSService;

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getContentListByType(Request $request, $type)
    {
        try {
            $validTypes = ['page', 'post', 'faq', 'news'];

            if (!in_array($type, $validTypes)) {
                throw new \Exception('Invalid type!! type should be of the following: ' . join(', ', $validTypes));
            }

            return $this->CMSService->contentListByType($request, $type);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function show(Request $request, $slug)
    {
        try {
            $item = $this->CMSService->show($request, $slug);

            return apiResponse($item);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function getPostsByCategory(Request $request, $slug)
    {
        try {
            return $this->CMSService->getPostsByCategory($request, $slug);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function getPostsByTag(Request $request, $slug)
    {
        try {
            return $this->CMSService->getPostsByTag($request, $slug);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
