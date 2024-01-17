@extends('layouts.auth')

@section('content')
    @include('partials.page_header',['content'=>'Register'])
    <section class="signup_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <form method="POST" action="{{ route('register') }}" class="ajax-form">
                        <div class="cardify signup_form">
                            <div class="login--header">
                                <h3>@lang('corals-marketplace-marty::labels.auth.no_account_register')</h3>
                                {{ csrf_field() }}
                            </div>
                            <div class="login--form">
                                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="urname">Your Name</label>
                                    <input id="urname" name="name" type="text"
                                           value="{{ old('name') }}" class="text_field"
                                           placeholder="@lang('User::attributes.user.name')">
                                </div>
                                @if ($errors->has('name'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label for="urname">Last Name</label>
                                    <input id="urname" name="last_name" type="text"
                                           value="{{ old('last_name') }}"
                                           class="text_field"
                                           placeholder="@lang('User::attributes.user.last_name')">
                                </div>
                                @if ($errors->has('last_name'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email_ad">Email Address</label>
                                    <input id="email_ad" type="email"
                                           value="{{ old('email') }}"
                                           name="email" class="text_field"
                                           placeholder="@lang('User::attributes.user.email')">
                                </div>
                                @if ($errors->has('email'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" name="password" class="text_field"
                                           placeholder="@lang('User::attributes.user.password')">
                                </div>
                                @if ($errors->has('password'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="con_pass">@lang('User::attributes.user.retype_password')</label>
                                    <input id="con_pass" type="password" name="password_confirmation"
                                           class="text_field"
                                           placeholder="@lang('User::attributes.user.retype_password')">
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </div>
                                @endif
                                @if( $is_two_factor_auth_enabled = \TwoFactorAuth::isActive())
                                    @if( $twoFaView = \TwoFactorAuth::TwoFARegistrationView())
                                        <div id="2fa-registration-details">
                                            @include($twoFaView)
                                        </div>
                                    @endif
                                @endif
                                <div class="form-group has-feedback {{ $errors->has('terms') ? ' has-error' : '' }}">
                                    <div class="custom_checkbox">
                                        <input name="terms" value="1" type="checkbox" id="ch2">
                                        <label for="ch2">
                                            <span class="shadow_checkbox"></span>
                                            @lang('corals-marketplace-marty::labels.auth.agree') <a href="#"
                                                                                                    data-toggle="modal"
                                                                                                    id="terms-anchor"
                                                                                                    data-target="#terms">
                                            <span class="label_text">
                                          <strong>@lang('corals-marketplace-marty::labels.auth.terms')</strong>
                                            </span>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                                @if ($errors->has('terms'))
                                    <span class="help-block"><strong>@lang('corals-marketplace-master::labels.auth.accept_terms')</strong></span>
                                @endif
                                <button class="btn btn--md btn--round register_btn"
                                        type="submit">@lang('corals-marketplace-marty::labels.auth.register')
                                </button>
                                <div class="login_assist">
                                    <p>@lang('corals-marketplace-marty::labels.auth.already_have_account') ?
                                        <a href="{{route('login')}}">@lang('corals-marketplace-marty::labels.auth.login')</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @component('components.modal',['id'=>'terms','header'=>\Settings::get('site_name').' Terms and policy'])
        {!! \Settings::get('terms_and_policy') !!}
    @endcomponent

@endsection
