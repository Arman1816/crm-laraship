@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_categories') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    @parent
    @if (user()->can('update', \Corals\Modules\Marketplace\Models\Category::class))
        {!! CoralsForm::link(url($resource_url.'/hierarchy'), trans('Marketplace::labels.category.hierarchy'), ['class'=>'btn btn-info']) !!}
    @endif
    @if(user()->can('create',\Corals\Modules\Marketplace\Models\Category::class))
        {!! CoralsForm::link(url($resource_url.'/get-import-modal'),
            trans('Marketplace::import.labels.import'),
            ['class' => 'btn btn-primary','data'=>[
                'action' => 'modal-load',
                'title' => trans('Marketplace::import.labels.import'),
            ]]) !!}
    @endif
@endsection
