@extends('layouts.auth')

@section('editable_content')
    <!-- Page Content-->
    <!-- Off-Canvas Wrapper-->
    <div class="offcanvas-wrapper">
        <!-- Page Title-->
        <div class="page-title">
            <div class="container">
                <div class="column">
                    <h1>@lang('corals-marketplace-master::labels.auth.login_register')</h1>
                </div>
            </div>
        </div>
        <div class="container padding-bottom-3x mb-2">
            <div class="row">
                <div class="col-md-5">
                    <form method="post" action="{{ route('login') }}" id="login-form"
                          class="login-box">
                        {{ csrf_field() }}
                        <div class="p-2">
                            @php \Actions::do_action('pre_login_form') @endphp
                        </div>
                        <h3>@lang('corals-marketplace-master::labels.auth.sign_in_start_session')</h3>

                        <div class="row margin-bottom-1x">
                            @if(config('services.facebook.client_id'))


                                <div class="col-xl-4 col-md-6 col-sm-4"><a class="btn btn-sm btn-block facebook-btn"
                                                                           href="{{ route('auth.social', 'facebook') }}"><i
                                                class="socicon-facebook"></i>&nbsp;@lang('corals-marketplace-master::labels.auth.sign_in_facebook')
                                    </a></div>
                            @endif
                            @if(config('services.twitter.client_id'))

                                <div class="col-xl-4 col-md-6 col-sm-4"><a class="btn btn-sm btn-block twitter-btn"
                                                                           href="{{ route('auth.social', 'twitter') }}"><i
                                                class="socicon-twitter"></i>&nbsp;@lang('corals-marketplace-master::labels.auth.sign_in_twitter')
                                    </a></div>
                            @endif
                            @if(config('services.google.client_id'))
                                <div class="col-xl-4 col-md-6 col-sm-4"><a class="btn btn-sm btn-block google-btn"
                                                                           href="{{ route('auth.social', 'google') }}"><i
                                                class="socicon-googleplus"></i>&nbsp;@lang('corals-marketplace-master::labels.auth.sign_in_google')
                                    </a></div>
                            @endif

                        </div>

                        <h4 class="margin-bottom-1x">@lang('corals-marketplace-master::labels.auth.or_using_form')</h4>

                        <div class="form-group text-center">
                            @if(session('confirmation_user_id'))
                                <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                            @endif
                        </div>
                        <div class="form-group input-group d-block has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="input-icon">
                                <i class="lni-user"></i>
                                <input type="text" id="email" class="form-control" name="email"
                                       placeholder="@lang('User::attributes.user.email')"
                                       value="{{ old('email') }}" autofocus><span
                                        class="input-group-addon"><i class="icon-mail"></i></span>
                            </div>
                            @if ($errors->has('email'))
                                <div class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </div>
                            @endif

                        </div>
                        <div class="form-group input-group d-block has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="input-icon">
                                <i class="lni-lock"></i>
                                <input type="password" name="password" class="form-control" id="password"
                                       placeholder="@lang('User::attributes.user.password')"><span
                                        class="input-group-addon"><i class="icon-lock"></i></span>
                            </div>
                            @if ($errors->has('password'))
                                <div class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-3 has-feedback">
                            <div class="checkbox">
                                <input type="checkbox"
                                       name="remember" {{ old('remember') ? 'checked' : '' }}/>
                                @lang('corals-marketplace-master::labels.auth.remember_me')
                            </div>
                        </div>
                        <div class="text-center text-sm-right">
                            <button type="submit"
                                    class="btn btn-primary margin-bottom-none">@lang('corals-marketplace-master::labels.auth.login')</button>
                        </div>

                    </form>
                </div>
                <div class="col-md-7">
                    <form method="POST" action="{{ route('register') }}" class="ajax-form login-box">
                        <div class="padding-top-3x hidden-md-up"></div>
                        <h3 class="margin-bottom-1x">@lang('corals-marketplace-master::labels.auth.no_account_register')</h3>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6" id="first-name-col">
                                <div class="form-group has-feedback {{ $errors->has('name') ? ' has-error' : '' }}">
                                    <input type="text" name="name"
                                           class="form-control" placeholder="@lang('User::attributes.user.name')"
                                           value="{{ old('name') }}" autofocus/>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>

                                    @if ($errors->has('name'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6" id="last-name-col">
                                <div class="form-group has-feedback {{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <input type="text" name="last_name"
                                           class="form-control"
                                           placeholder="@lang('User::attributes.user.last_name')"
                                           value="{{ old('last_name') }}" autofocus/>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>

                                    @if ($errors->has('last_name'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" name="email"
                                           class="form-control" placeholder="@lang('User::attributes.user.email')"
                                           value="{{ old('email') }}"/>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                                    @if ($errors->has('email'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input type="password" name="password" class="form-control"
                                           placeholder="@lang('User::attributes.user.password')"/>
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                    @if ($errors->has('password'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <input type="password" name="password_confirmation" class="form-control"
                                           placeholder="@lang('User::attributes.user.retype_password')"/>
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                    @if ($errors->has('password_confirmation'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </div>
                        @if( $is_two_factor_auth_enabled = \TwoFactorAuth::isActive())
                            @if( $twoFaView = \TwoFactorAuth::TwoFARegistrationView())
                                <div id="2fa-registration-details">
                                    @include($twoFaView)
                                </div>
                            @endif
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has-feedback {{ $errors->has('terms') ? ' has-error' : '' }}">
                                    <div class="checkbox icheck">
                                        <label>
                                            <input name="terms" value="1" type="checkbox"/>
                                            <strong>@lang('corals-marketplace-master::labels.auth.agree')
                                                <a href="#" data-toggle="modal" id="terms-anchor"
                                                   data-target="#terms">@lang('corals-marketplace-master::labels.auth.terms')</a>
                                            </strong>
                                        </label>
                                    </div>
                                    @if ($errors->has('terms'))
                                        <span class="help-block"><strong>@lang('corals-marketplace-master::labels.auth.accept_terms')</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 text-center text-sm-right">
                                <button type="submit" class="btn btn-primary margin-bottom-none"
                                >@lang('corals-marketplace-master::labels.auth.register')</button>

                                <a href="{{ route('password.request') }}"
                                   class="btn bg-yellow btn-social btn-primary">
                                    <span class="fa fa-question"></span>
                                    @lang('corals-marketplace-master::labels.auth.forget_password')
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @component('components.modal',['id'=>'terms','header'=>\Settings::get('site_name').' Terms and policy'])
        {!! \Settings::get('terms_and_policy') !!}
    @endcomponent
@endsection
