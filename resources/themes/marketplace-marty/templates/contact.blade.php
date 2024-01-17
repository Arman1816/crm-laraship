@extends('layouts.public')

@section('content')
    <section class="breadcrumb-area breadcrumb--center breadcrumb--smsbtl">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page_title">
                        <h3>{!! $item->title !!}</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <p>{!! $item->rendered !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="contact_tile">
                                <span class="tiles__icon lnr lnr-map-marker"></span>
                                <h4 class="tiles__title">Office Address</h4>
                                <div class="tiles__content">
                                    <p>{!!  \Settings::get('contact_address','Al-Masyoun, Ramallah, Palestine.')  !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="contact_tile">
                                <span class="tiles__icon lnr lnr-phone"></span>
                                <h4 class="tiles__title">Phone Number</h4>
                                <div class="tiles__content">
                                    <p>{{ \Settings::get('contact_mobile','+970599593301') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="contact_tile">
                                <span class="tiles__icon lnr lnr-inbox"></span>
                                <h4 class="tiles__title">Email address</h4>
                                <div class="tiles__content">
                                    <p>{{ \Settings::get('contact_form_email','support@example.com') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="contact_form cardify">
                                <div class="contact_form__title">
                                    <h3>Contact Us</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 offset-md-2">
                                        <div class="contact_form--wrapper">
                                            <form id="main-contact-form" class="contact-form ajax-form"
                                                  name="contact-form" method="post"
                                                  data-page_action="clearContactForm"
                                                  action="{{ url('contact/email') }}">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" name="name"
                                                                   placeholder="@lang('corals-marketplace-marty::labels.template.contact.name')">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="email" name="email"
                                                                   placeholder="@lang('corals-marketplace-marty::labels.template.contact.email')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" name="phone"
                                                                   placeholder="@lang('corals-marketplace-marty::labels.template.contact.phone')">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" name="company"
                                                                   placeholder="@lang('corals-marketplace-marty::labels.template.contact.company_name')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="text" name="subject"
                                                                   placeholder="@lang('corals-marketplace-marty::labels.template.contact.subject')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <textarea cols="30" rows="10" name="message"
                                                          placeholder="@lang('corals-marketplace-marty::labels.template.contact.message')"></textarea>

                                                <div class="form-group">

                                                    {!! NoCaptcha::display() !!}

                                                </div>
                                                <div class="sub_btn">
                                                    <button type="submit" name="submit"
                                                            class="btn btn--round btn--default">
                                                        @lang('corals-marketplace-marty::labels.template.contact.submit_message')
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="map">
        <iframe src="{{ \Settings::get('google_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3387.331591494841!2d35.19981536504809!3d31.897586781246385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x518201279a8595!2sLeaders!5e0!3m2!1sen!2s!4v1512481232226') }}"
                width="100%" height="500" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
@endsection

@section('js')

    {!! NoCaptcha::renderJs() !!}

@endsection
