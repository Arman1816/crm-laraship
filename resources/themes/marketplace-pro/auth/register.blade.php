@extends('layouts.public')

@section('content')
  
    <div class="user-login ">
        <div id="wrapper-site">
            <div id="content-wrapper" class="full-width" style="margin: 30px;padding-top: 30px; border-radius:5px; box-shadow: #091E08 0px 1px 3px, #091E08 0px 1px 2px;">
                <div id="main">
                    <div class="container">
                        <h1 style="margin-top: -50px;" class="text-center title-page">@lang('corals-marketplace-pro::labels.auth.no_account_register')</h1>
                        <form style="margin-top: -30px;" method="POST" action="{{ route('register') }}"
                              class="ajax-form login-box js-customer-form"
                              id="customer-form">
                            {{ csrf_field() }}
                            <div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <div class="form-group" id="first-name-col">
                                            <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <input style="border: solid 1px #091E08; border-radius: 5px; margin-bottom: -4px;" type="text" name="name"
                                                       class="form-control"
                                                       placeholder="@lang('User::attributes.user.name')"
                                                       value="{{ old('name') }}" autofocus/>
                                                @if ($errors->has('name'))
                                                    <div class="help-block">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group" id="last-name-col">
                                            <div class="{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                                <input style="border: solid 1px #091E08; border-radius: 5px; margin-bottom: -4px;" type="text" name="last_name"
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
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <input style="border: solid 1px #091E08; border-radius: 5px; margin-bottom: -4px;" type="email" name="email"
                                                       class="form-control"
                                                       placeholder="@lang('User::attributes.user.email')"
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
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <div class="input-group js-parent-focus">
                                                    <input style="border: solid 1px #091E08; border-radius: 5px; margin-bottom: -4px;" type="password" name="password" class="form-control"
                                                           placeholder="@lang('User::attributes.user.password')"/>
                                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                                    @if ($errors->has('password'))
                                                        <div class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                                <div class="input-group js-parent-focus">
                                                    <input style="border: solid 1px #091E08; border-radius: 5px; margin-bottom: -4px;" type="password" name="password_confirmation"
                                                           class="form-control"
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
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            @if( $is_two_factor_auth_enabled = \TwoFactorAuth::isActive())
                                                @if( $twoFaView = \TwoFactorAuth::TwoFARegistrationView())
                                                    <div id="2fa-registration-details">
                                                        @include($twoFaView)
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <div class="{{ $errors->has('terms') ? ' has-error' : '' }}">
                                                <div class="checkbox icheck">
                                                    <label>
                                                        <input name="terms" value="1" type="checkbox"/>
                                                        <strong>@lang('corals-marketplace-pro::labels.auth.agree')
                                                            <a style="color:black" href="#" data-toggle="modal" id="terms-anchor"
                                                               style="color:#41a5d2;"
                                                               data-target="#terms">@lang('corals-marketplace-pro::labels.auth.terms')</a>
                                                </strong>
                                            </label>
                                        </div>
                                        @if ($errors->has('terms'))
                                            <span class="help-block"><strong>@lang('corals-marketplace-pro::labels.auth.accept_terms')</strong></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <div class="text-center text-sm-right">
                                        <button type="submit" style="color:white; background-color:#091E08; border-radius: 5px; margin: -7px; 0px; margin-right:20px; color:white" class="btn btn">
                                            @lang('corals-marketplace-pro::labels.auth.register')
                                        </button>
                                        <a style="color:white; background-color:#091E08; border-radius: 5px; margin: -7px; 0px; color:white" class="btn  margin-bottom-none check-out" href="{{ route('login') }}"
                                           rel="nofollow"
                                           title="Checkout">
                                            <span> @lang('corals-marketplace-pro::labels.partial.login')</span>
                                        </a>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@component('components.modal',['id'=>'terms','header'=>\Settings::get('site_name').' Terms and policy'])
    {!! \Settings::get('terms_and_policy') !!}
@endcomponent