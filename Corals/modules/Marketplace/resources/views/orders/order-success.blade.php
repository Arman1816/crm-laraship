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
    @component('components.box',['box_class'=>'box-success'])

        <div class="container">
            <div class="row text-center">
                <div class="col-md-12">
                    <br><br>
                    <h2 style="color:#0fad00">@lang('Marketplace::labels.order.success')</h2>
                    <p style="font-size:20px;color:#5C5C5C;">@lang('Marketplace::labels.order.order_has_been_placed')</p>
                    <a href="{{ url('marketplace/orders/my') }}"
                       class="btn btn-success">@lang('Marketplace::labels.order.go_to_my_order')</a>
                    <br><br>
                </div>

            </div>
        </div>
    @endcomponent
@endsection

