@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_attribute_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! CoralsForm::openForm($attribute) !!}

                <div class="row">
                    <div class="col-md-4">
                        {!! CoralsForm::text('code','Marketplace::attributes.attributes.code',true) !!}
                        {!! CoralsForm::select('type', 'Marketplace::attributes.attributes.type', get_array_key_translation(config('settings.models.custom_field_setting.supported_types')), true,$attribute->type,$attribute->exists?['readonly']:[]) !!}
                        {!! CoralsForm::text('label','Marketplace::attributes.attributes.label',true) !!}
                        {!! CoralsForm::number('display_order','Marketplace::attributes.attributes.order',true,0,['min'=>0]) !!}
                        {!! CoralsForm::checkbox('required', 'Marketplace::attributes.attributes.required', $attribute->required) !!}

                        {!! CoralsForm::select('attribute_sets[]', 'Marketplace::attributes.attribute_set.sets', \Marketplace::getAttributeSetsList(),
                    false, $attribute->attributeSets()->pluck('set_id')->toArray(), ['multiple'=>true], 'select2') !!}

                        {!! \Store::getStoreFields($attribute) !!}

                    </div>
                    <div class="col-md-4">

                        <div style="display: none;" id="options-field">
                            <div class="form-group" style="">
                                <span data-name="options"></span>
                                {!! CoralsForm::label('options', 'Marketplace::attributes.attributes.options') !!}
                                @include('Marketplace::attributes.options',[
                                                                'name'=>'options',

                                'options'=> $attribute->options??[],
                                'displayType' => $attribute->getProperty('display_type','label')
                                ])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>

                {!! CoralsForm::closeForm($attribute) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')

    <script type="text/javascript">
        $(document).ready(function () {
            var $type = $("#type");

            var options_types = ['select', 'radio', 'multi_values'];
            if (_.includes(options_types, $type.val())) {
                $("#options-field").fadeIn();
            }

            $type.change(function (event) {
                if (_.includes(options_types, $(this).val())) {
                    $("#options-field").fadeIn();
                } else {
                    $("#options-field").fadeOut();
                }
            })
        });
    </script>
@endsection
