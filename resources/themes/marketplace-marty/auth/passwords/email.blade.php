@extends('layouts.auth')

@section('content')

    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb">
                        <ul>
                            <li>
                                <a href="#">@lang('corals-marketplace-marty::labels.auth.reset_password')</a>
                            </li>
                            <li class="active">
                                <a href="#">@lang('corals-marketplace-marty::labels.auth.reset_password')</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="login_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="post" action="{{route('password.email')}}" id="login-form">
                        {{ csrf_field() }}
                        <div class="cardify login">
                            <div class="login--header">
                                @php \Actions::do_action('pre_login_form') @endphp
                                <h3>Welcome Back</h3>
                                <p>@lang('corals-marketplace-marty::labels.auth.sign_in_start_session')</p>
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