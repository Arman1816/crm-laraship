@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_shippings') }}
        @endslot
    @endcomponent
@endsection

@section('custom-actions')
    @if(session()->has('shipping-rules-report'))
        <div class="row">
            <div class="col-md-12">
                {!! CoralsForm::link(url($resource_url.'/import-report/download'),'Marketplace::labels.shipping.download_import_report',['class'=>'btn btn-info','target'=>'_blank']) !!}
                {!! CoralsForm::link(url($resource_url.'/import-report/clear'),'Marketplace::labels.shipping.clear_import_report',['class'=>'btn btn-warning']) !!}
            </div>
        </div>
    @endif
@endsection