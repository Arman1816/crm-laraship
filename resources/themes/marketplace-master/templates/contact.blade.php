@extends('layouts.public')

@section('editable_content')
    <div class="map">
        <iframe src="{{ \Settings::get('google_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3387.331591494841!2d35.19981536504809!3d31.897586781246385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x518201279a8595!2sLeaders!5e0!3m2!1sen!2s!4v1512481232226') }}"
                width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
    <div class="container padding-bottom-3x mb-1">
        {!! $item->rendered !!}
        <div>
            <form id="main-contact-form" class="contact-form ajax-form" name="contact-form" method="post"
                  data-page_action="clearContactForm"
                  action="{{ url('contact/email') }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>@lang('corals-marketplace-master::labels.template.contact.name')</label>
                            <input type="text" name="name" class="form-control form-control-rounded">
                        </div>
                        <div class="form-group">
                            <label>@lang('corals-marketplace-master::labels.template.contact.email')</label>
                            <input type="email" name="email" class="form-control form-control-rounded">
                        </div>
                        <div class="form-group">
                            <label>@lang('corals-marketplace-master::labels.template.contact.phone')</label>
                            <input type="text" name="phone" class="form-control form-control-rounded">
                        </div>
                        <div class="form-group">
                            <label>@lang('corals-marketplace-master::labels.template.contact.company_name')</label>
                            <input type="text" name="company" class="form-control form-control-rounded">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>@lang('corals-marketplace-master::labels.template.contact.subject')</label>
                            <input type="text" name="subject" class="form-control form-control-rounded">
                        </div>
                        <div class="form-group">
                            <label>@lang('corals-marketplace-master::labels.template.contact.message')</label>
                            <textarea name="message" id="message"
                                      class="form-control form-control-rounded"
                                      rows="10"></textarea>
                        </div>
                        <div class="form-group">

                            {!! NoCaptcha::display() !!}

                        </div>
                        <div class="form-group text-right">
                            <button type="submit" name="submit" class="btn btn-outline-primary" required="required">
                                @lang('corals-marketplace-master::labels.template.contact.submit_message')
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')

    {!! NoCaptcha::renderJs() !!}

@endsection
