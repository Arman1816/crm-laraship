@extends('layouts.auth')

@section('content')
    <div class="wrap-banner">
        <nav class="breadcrumb-bg">
            <div class="container no-index">
                <div class="breadcrumb">
                    <ol>
                        <li>
                            <a href="#">
                                <span>@lang('corals-marketplace-pro::labels.auth.login_register')</span>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </nav>
    </div>
    <div class="user-login ">
        <div id="wrapper-site">
            <div id="content-wrapper" class="full-width">
                <div id="main">
                    <div class="container">
                        <h1 class="text-center title-page">@lang('corals-marketplace-pro::labels.auth.reset_password')</h1>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.request') }}" class="login-form"
                              id="customer-form">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" name="email"
                                       class="form-control" placeholder="@lang('User::attributes.user.email')"
                                       value="{{ old('email', $email) }}"/>
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                                @if ($errors->has('email'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>
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
                            <div class="row justify-content-center">
                                <!-- /.col -->
                                <div class="col-xs-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-block btn-flat">@lang('corals-marketplace-pro::labels.auth.reset_password')</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
