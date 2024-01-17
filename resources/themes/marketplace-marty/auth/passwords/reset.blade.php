@extends('layouts.auth')

@section('content')
    @include('partials.page_header',['content'=>'Reset Password'])
    <section class="login_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.request') }}" id="login-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="cardify login">
                            <div class="login--header">
                                <h3>@lang('corals-marketplace-marty::labels.auth.reset_password')</h3>
                            </div>
                            <div class="login--form">
                                <div class="form-group">
                                    @if(session('confirmation_user_id'))
                                        <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="user_name">Username</label>
                                    <input id="email" name="email" type="text" class="text_field"
                                           placeholder="@lang('User::attributes.user.email')" value="{{old('email', $email)}}">
                                </div>
                                @if ($errors->has('email'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="pass">password</label>
                                    <input type="password" name="password" id="password" class="text_field"
                                           placeholder="@lang('User::attributes.user.password')">
                                </div>
                                @if ($errors->has('password'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="pass">password_confirmation</label>
                                    <input type="password" name="password_confirmation" id="password" class="text_field"
                                           placeholder="@lang('User::attributes.user.password')">
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </div>
                                @endif
                                <button class="btn btn--md btn--round"
                                        type="submit">
                                    @lang('corals-marketplace-marty::labels.auth.send_password_reset')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
