@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_sku_create_edit',$product) }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-6">
            @component('components.box')
                {!! CoralsForm::openForm(null, ['url'=>url($resource_url.'/generate-skus')]) !!}
                <div class="row">
                    <div class="col-md-6">
                        <h4>@lang('Marketplace::attributes.product.variation_options')</h4>
                        <hr/>
                        {!! $product->renderProductOptionsForBulk('variation_options', null)  !!}
                    </div>
                    <div class="col-md-6">
                        <h4>@lang('Marketplace::labels.bulkGenerate.variation_generate_options')</h4>
                        <hr/>
                        {!! CoralsForm::radio('generate_option','Marketplace::labels.bulkGenerate.variation_generate_options_label', true,
                            $options = trans('Marketplace::labels.bulkGenerate.variation_generate_options_list'),
                            array_keys($options)[0],
                            ['radio_wrapper'=>'div']) !!}

                        {!! \CoralsForm::button('Marketplace::labels.bulkGenerate.generate',
                            ['class'=>'btn btn-success btn-sm'], 'submit') !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm() !!}
            @endcomponent
        </div>
    </div>
@endsection
