<?php

namespace Corals\Modules\CMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\CMS\DataTables\TestimonialsDataTable;
use Corals\Modules\CMS\Http\Requests\TestimonialRequest;
use Corals\Modules\CMS\Models\Testimonial;
use Corals\Modules\CMS\Services\TestimonialService;

class TestimonialsController extends BaseController
{
    protected $testimonialService;

    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonialService = $testimonialService;

        $this->resource_url = config('cms.models.testimonial.resource_url');
        $this->title = 'CMS::module.testimonial.title';
        $this->title_singular = 'CMS::module.testimonial.title_singular';

        parent::__construct();
    }

    /**
     * @param TestimonialRequest $request
     * @param TestimonialsDataTable $dataTable
     * @return mixed
     */
    public function index(TestimonialRequest $request, TestimonialsDataTable $dataTable)
    {
        return $dataTable->render('CMS::testimonials.index');
    }

    /**
     * @param TestimonialRequest $request
     * @return $this
     */
    public function create(TestimonialRequest $request)
    {
        $testimonial = new Testimonial();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('CMS::testimonials.create_edit')->with(compact('testimonial'));
    }

    /**
     * @param TestimonialRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TestimonialRequest $request)
    {
        try {
            $testimonial = $this->testimonialService->store($request, Testimonial::class, ['author_id' => user()->id]);
            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Testimonial::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param TestimonialRequest $request
     * @param Testimonial $testimonial
     * @return Testimonial
     */

    public function show(TestimonialRequest $request, Testimonial $testimonial)
    {
        return $testimonial;
    }

    /**
     * @param TestimonialRequest $request
     * @param Testimonial $testimonial
     * @return $this
     */
    public function edit(TestimonialRequest $request, Testimonial $testimonial)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $testimonial->title])]);

        return view('CMS::testimonials.create_edit')->with(compact('testimonial'));
    }

    /**
     * @param TestimonialRequest $request
     * @param Testimonial $testimonial
     * @return $this
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial)
    {
        try {
            $testimonial = $this->testimonialService->update($request, $testimonial);
            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Testimonial::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    public function destroy(TestimonialRequest $request, Testimonial $testimonial)
    {
        try {
            $this->testimonialService->destroy($request, $testimonial);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $testimonial->title])];
        } catch (\Exception $exception) {
            log_exception($exception, Testimonial::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
