@extends('layouts.auth')

@section('editable_content')
    <!-- Page Content-->
    <!-- Off-Canvas Wrapper-->
    <div class="offcanvas-wrapper">
        <!-- Page Title-->
        <div class="page-title">
            <div class="container">
                <div class="column">
                    <h1>@lang('corals-marketplace-master::labels.auth.reset_password')</h1>
                </div>
            </div>
        </div>
        <div class="container padding-bottom-3x mb-2">
            <div class="row justify-content-center">
                <div class=" col-md-6">

                    <h2>@lang('corals-marketplace-master::labels.auth.forget_password')</h2>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('password.email') }}" id="login-form"
                          class="login-box">
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
                                        class="btn btn-primary btn-block btn-flat">@lang('corals-marketplace-master::labels.auth.send_password_reset')</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection