@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_cart') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    @component('components.box',['box_class'=>'box-danger'])

        <div class="container">
            <div class="row text-center">
                <div class="col-md-12">
                    <br><br>
                    <h2 style="color:#fa0000">@lang('Marketplace::labels.order.failed')</h2>
                    <p style="font-size:20px;color:#5C5C5C;"> @if(session()->has('failed-message'))
                        {!! session('failed-message') !!}
                    @else
                        @lang('Marketplace::messages.order.payment_failed')
                    @endif</p>
                    @auth
                        <a href="{{ url('marketplace/orders/my') }}"
                           class="btn btn-info">@lang('Marketplace::labels.order.go_to_my_order')</a>
                    @endauth
                    <br><br>
                </div>
            </div>
        </div>
    @endcomponent
@endsection

