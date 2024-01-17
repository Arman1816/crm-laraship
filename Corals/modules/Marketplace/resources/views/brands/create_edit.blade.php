@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_brand_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($brand,['files'=>true]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','Marketplace::attributes.brand.name',true) !!}
                        {!! CoralsForm::text('slug','Marketplace::attributes.brand.slug',true) !!}
                        {!! CoralsForm::checkbox('is_featured', 'Marketplace::attributes.brand.is_featured', $brand->is_featured) !!}
                        {!! CoralsForm::radio('status','Corals::attributes.status',true, trans('Corals::attributes.status_options')) !!}
                        {!! \Store::getStoreFields($brand) !!}

                    </div>
                    <div class="col-md-6">
                        @if($brand->hasMedia($brand->mediaCollectionName))
                            <img src="{{ $brand->thumbnail }}" class="img-responsive img-fluid" style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            {!! CoralsForm::checkbox('clear', 'Marketplace::attributes.brand.clear') !!}
                        @endif
                        {!! CoralsForm::file('thumbnail', 'Marketplace::attributes.brand.thumbnail') !!}
                    </div>
                </div>
                {!! CoralsForm::customFields($brand, 'col-md-12') !!}
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}

                    </div>
                </div>
                {!! CoralsForm::closeForm($brand) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
@endsection
