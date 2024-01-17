@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_sku', $product) }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    @parent
    {!! CoralsForm::link(url($product->getOriginalShowURL()), trans('Marketplace::labels.product.back_to_product',['name'=>'']),['class'=>'btn btn-info']) !!}
    @if(user()->can('bulkEditSKUs', $product))
        {!! CoralsForm::link(url($resource_url.'/bulk-edit'), trans('Marketplace::labels.bulkGenerate.bulk-edit'),['class'=>'btn btn-primary']) !!}
    @endif
    @if(user()->can('create',\Corals\Modules\Marketplace\Models\SKU::class))
        {!! CoralsForm::link(url($resource_url.'/bulk-generate'), trans('Marketplace::labels.bulkGenerate.bulk-generate'),['class'=>'btn btn-success']) !!}
    @endif
@endsection
