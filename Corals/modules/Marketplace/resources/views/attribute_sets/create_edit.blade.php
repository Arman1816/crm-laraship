@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_attribute_set_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-4">
            @component('components.box')
                {!! CoralsForm::openForm($attributeSet) !!}
                {!! CoralsForm::text('code','Marketplace::attributes.attribute_set.code', true) !!}
                {!! CoralsForm::text('name','Marketplace::attributes.attribute_set.name', true) !!}
                {!! CoralsForm::checkbox('is_default', 'Marketplace::attributes.attribute_set.is_default', $attributeSet->is_default) !!}

                {!! CoralsForm::select('attributes[]', 'Marketplace::attributes.attribute_set.attributes', \Marketplace::getAttributesList(),
                    false, $attributeSet->productAttributes()->pluck('model_id')->toArray(), ['multiple'=>true], 'select2') !!}
                {!! \Store::getStoreFields($attributeSet) !!}

                {!! CoralsForm::formButtons() !!}
                {!! CoralsForm::closeForm($attributeSet) !!}
            @endcomponent
        </div>
    </div>
@endsection
