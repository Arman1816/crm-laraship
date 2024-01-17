@extends('layouts.public')

@section('editable_content')
    @php \Actions::do_action('pre_content', null, null) @endphp
    <div class="container">
        <div class="row text-center">
            <div class="col-sm-12">
                <br><br>
                <h2 style="color:#fa0000">@lang('Marketplace::labels.order.failed')</h2>
                <p style="font-size:20px;color:#5C5C5C;">
                    @if(session()->has('failed-message'))
                        {!! session('failed-message') !!}
                    @else
                        @lang('Marketplace::messages.order.payment_failed')
                    @endif
                </p>
                @auth
                    <a href="{{ url('marketplace/orders/my') }}"
                       class="btn btn-info">@lang('Marketplace::labels.order.go_to_my_order')</a>
                @endauth
                <br><br>
            </div>
        </div>
    </div>
@stop
