@extends('layouts.auth')

@section('content')
    @include('partials.page_header',['content'=>'login'])
    <section class="login_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <form method="post" action="{{ route('login') }}" id="login-form">
                        {{ csrf_field() }}
                        <div class="cardify login">
                            <div class="login--header">
                                @php \Actions::do_action('pre_login_form') @endphp
                                <h3>@lang('corals-marketplace-marty::labels.auth.sign_in_start_session')</h3>
                            </div>
                            <div class="login--form">
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="user_name">Username</label>
                                    <input id="email" name="email" type="text" class="text_field"
                                           placeholder="@lang('User::attributes.user.email')" value="{{old('email')}}">
                                </div>
                                @if ($errors->has('email'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="pass">Password</label>
                                    <input type="password" name="password" id="password" class="text_field"
                                           placeholder="@lang('User::attributes.user.password')">
                                </div>
                                @if ($errors->has('password'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <div class="custom_checkbox">
                                        <input type="checkbox"
                                               name="remember" {{ old('remember') ? 'checked' : '' }}
                                               id="ch2">
                                        <label for="ch2">
                                            <span class="shadow_checkbox"></span>
                                            <span class="label_text">@lang('corals-marketplace-marty::labels.auth.remember_me')</span>
                                        </label>
                                    </div>
                                </div>
                                <button class="btn btn--md btn--round"
                                        type="submit">@lang('corals-marketplace-marty::labels.auth.login')</button>
                                <div class="login_assist">
                                    <p class="recover">@lang('corals-marketplace-marty::auth.forget_password')
                                        <a href="{{ route('password.request') }}">
                                            @lang('corals-marketplace-marty::auth.click_here')
                                        </a>?</p>
                                    <p class="signup">
                                        <a href="{{route('register')}}">
                                            @lang('corals-marketplace-marty::labels.auth.no_account_register')
                                        </a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection