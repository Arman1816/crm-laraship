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
                        <form method="post" action="{{ route('password.email') }}" class="login-form" id="customer-form">
                            {{ csrf_field() }}
                            <div class="form-group text-center">
                                @if(session('confirmation_user_id'))
                                    <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                                @endif
                            </div>
                            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" name="email"
                                       class="form-control" placeholder="Email"
                                       value="{{ old('email') }}" autofocus/>
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                                @if ($errors->has('email'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="row justify-content-center">
                                <!-- /.col -->
                                <div class="col-xs-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-block btn-flat">@lang('corals-marketplace-pro::labels.auth.send_password_reset')</button>
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