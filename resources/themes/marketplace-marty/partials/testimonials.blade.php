@php $testimonials = \CMS::getTestimonialsList();@endphp
@if(!$testimonials->isEmpty())
    <section class="testimonial-area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h1>
                            @lang('corals-marketplace-marty::labels.template.home.testimonials')
                        </h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="testimonial-slider">
                        @foreach($testimonials = \CMS::getTestimonialsList() as $testimonial)
                            <div class="testimonial">
                                <div class="testimonial__about">
                                    <div class="avatar v_middle">
                                        <img src="{{$testimonial->image}}" alt="Testimonial Avatar">
                                    </div>
                                    <div class="name-designation v_middle">
                                        <h4 class="name">{!! $testimonial->title !!}</h4>
                                        <span class="desig">{!! $testimonial->getProperty('job_title') !!}</span>
                                    </div>
                                    <span class="lnr lnr-bubble quote-icon"></span>
                                </div>
                                <div class="testimonial__text">
                                    <p>{!! $testimonial->content !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="all-testimonial">
                        <a href="{{url('cms/testimonials')}}" class="btn btn--lg btn--round">
                            @lang('corals-marketplace-marty::labels.template.cart.view_all')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif