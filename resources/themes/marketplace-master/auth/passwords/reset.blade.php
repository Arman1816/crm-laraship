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

                    <h2>@lang('corals-marketplace-master::labels.auth.reset_password')</h2>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.request') }}" class="login-box"
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
                                        class="btn btn-primary btn-block btn-flat">@lang('corals-marketplace-master::labels.auth.reset_password')</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
