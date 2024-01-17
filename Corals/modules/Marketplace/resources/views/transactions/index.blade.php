@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('transactions') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row p-10">

        <div class="col-md-3 col-sm-6 col-xs-6">

            <div class="info-box  bg-aqua">
                <span class="info-box-icon "><i class="fa fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">@lang('Marketplace::labels.transactions.total_sales')</span>
                    <span class="info-box-number">{{\Payments::admin_currency($total_sales)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="info-box  bg-green">
                <span class="info-box-icon "><i class="fa fa-percent"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">@lang('Marketplace::labels.transactions.total_commission')</span>
                    <span class="info-box-number">{{\Payments::admin_currency($total_commission)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-6">

            <div class="info-box  bg-yellow">
                <span class="info-box-icon "><i class=" fa fa-arrow-circle-up"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">@lang('Marketplace::labels.transactions.total_completed_withdrawals')</span>
                    <span class="info-box-number">{{\Payments::admin_currency($total_completed_withdrawals)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-6">

            <div class="info-box bg-red">

                <span class="info-box-icon "><i class="fa fa-clock-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">@lang('Marketplace::labels.transactions.total_pending_withdrawals')</span>
                    <span class="info-box-number">{{\Payments::admin_currency( $total_pending_withdrawals)}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    @if(!\Store::isStoreAdmin())
        <div class="row m-t-10">
            <div class="col-md-12">
                <div class="alert alert-primary">
                    @if($balance)
                        <h6>{!!    trans('Marketplace::labels.transactions.current_balance_withdraw',['balance'=> \Payments::admin_currency( $balance),'withdraw_url'=>url($resource_url.'/withdraw') ])!!}</h6>
                    @else
                        <h6>{!!    trans('Marketplace::labels.transactions.current_balance',['balance'=> \Payments::admin_currency( $balance) ])!!}</h6>

                    @endif
                </div>
            </div>
        </div>
    @endif
    @parent
@endsection


@section('actions')
@endsection