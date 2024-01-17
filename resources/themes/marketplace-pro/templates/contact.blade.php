@extends('layouts.public')

@section('content')
    <div class="container" id="contact">
        <h1 class="text-center title-page">Contact Us</h1>
        <div class="row-inhert">
            <div class="header-contact">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="item d-flex">
                            <div class="item-left">
                                <div class="icon">
                                    <i class="zmdi zmdi-email"></i>
                                </div>
                            </div>
                            <div class="item-right d-flex">
                                <div class="title">Email:</div>
                                <div class="contact-content">
                                    <a href="mailto:support@domain.com">support@domain.com</a>
                                    <br>
                                    <a href="mailto:contact@domain.com">contact@domain.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="item d-flex">
                            <div class="item-left">
                                <div class="icon">
                                    <i class="zmdi zmdi-home"></i>
                                </div>
                            </div>
                            <div class="item-right d-flex">
                                <div class="title">Address:</div>
                                <div class="contact-content">
                                    23 Suspendis matti, Visaosang Building
                                    <br>District, NY Accums, North American
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="item d-flex justify-content-end  last">
                            <div class="item-left">
                                <div class="icon">
                                    <i class="zmdi zmdi-phone"></i>
                                </div>
                            </div>
                            <div class="item-right d-flex">
                                <div class="title">Hotline:</div>
                                <div class="contact-content">
                                    0123-456-78910
                                    <br>0987-654-32100
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contact-map">
                <div id="map">
                    <iframe src="{{ \Settings::get('google_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3387.331591494841!2d35.19981536504809!3d31.897586781246385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x518201279a8595!2sLeaders!5e0!3m2!1sen!2s!4v1512481232226') }}"
                            width="1110" height="380" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="input-contact">
                <p class="text-intro text-center">{!! $item->rendered !!}
                </p>
                <div class="d-flex justify-content-center">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="contact-form">
                            <form id="main-contact-form" class="contact-form ajax-form" name="contact-form"
                                  method="post" data-page_action="clearContactForm"
                                  action="{{ url('contact/email') }}">
                                {{ csrf_field() }}
                                <div class="form-fields">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <input class="form-control" type="text" name="name"
                                                   placeholder="@lang('corals-marketplace-pro::labels.template.contact.name')">
                                        </div>
                                        <div class="col-md-6 margin-bottom-mobie">
                                            <input class="form-control" type="email" name="email"
                                                   placeholder="@lang('corals-marketplace-pro::labels.template.contact.email')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 margin-bottom-mobie">
                                            <input class="form-control" type="text" name="phone"
                                                   placeholder="@lang('corals-marketplace-pro::labels.template.contact.phone')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 margin-bottom-mobie">
                                            <input class="form-control" type="text" name="company"
                                                   placeholder="@lang('corals-marketplace-pro::labels.template.contact.company_name')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 margin-bottom-mobie">
                                            <input class="form-control" type="text" name="subject"
                                                   placeholder="@lang('corals-marketplace-pro::labels.template.contact.subject')">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <textarea class="form-control" name="message" id="message"
                                                      placeholder="@lang('corals-marketplace-pro::labels.template.contact.message')"
                                                      rows="8"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">

                                            {!! NoCaptcha::display() !!}

                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-block" style="border-radius: .25rem;" type="submit"
                                            name="submit" required="required">
                                        @lang('corals-marketplace-pro::labels.template.contact.submit_message')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

@section('js')

    {!! NoCaptcha::renderJs() !!}

@endsection