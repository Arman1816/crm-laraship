@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_package_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($package) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','Marketplace::attributes.package.name', true, null, []) !!}
                        {!! \Store::getStoreFields($package) !!}
                        {!! CoralsForm::textarea('description','Marketplace::attributes.package.description', false, null, ['rows'=>3]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! CoralsForm::select('dimension_template','Marketplace::attributes.package.template', \ShippingPackages::getPackagesTemplates(),
                            false, null,['help_text'=>'Marketplace::attributes.package.template_help'], 'select2') !!}

                        <div class="row dimensions-fields">
                            <div class="col-md-6">
                                {!! CoralsForm::number('length','Marketplace::attributes.package.length', true, number_format($package->length, 1), ['min'=>0, 'step'=>0.01]) !!}
                                {!! CoralsForm::number('height','Marketplace::attributes.package.height', true, number_format($package->height, 1), ['min'=>0, 'step'=>0.01]) !!}
                            </div>
                            <div class="col-md-6">
                                {!! CoralsForm::number('width','Marketplace::attributes.package.width', true, number_format($package->width, 1), ['min'=>0, 'step'=>0.01]) !!}
                                {!! CoralsForm::select('distance_unit','Marketplace::attributes.package.distance_unit', $package->getConfig('distance_unit_options'),true, null, []) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {!! CoralsForm::number('weight','Marketplace::attributes.package.weight', true, number_format($package->weight, 1), ['min'=>0, 'step'=>0.01,'help_text'=>'Marketplace::attributes.package.weight_help']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! CoralsForm::select('mass_unit','Marketplace::attributes.package.mass_unit', $package->getConfig('mass_unit_options'), true, null, []) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {!! CoralsForm::customFields($package, 'col-md-6') !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($package) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script>
        function setDimensionsFieldsState() {
            $('.dimensions-fields select,.dimensions-fields input').attr('readOnly', $("#dimension_template").val().length ? 'readOnly' : false);
        }

        $('#dimension_template').on('change select2:clear', function (event) {
            setDimensionsFieldsState();
            let element = $(this);

            if (!element.val()) {
                return false;
            }

            $.get("{{ url('marketplace/packages/get-template-by-code') }}" + "/" + element.val(), function (response) {
                if (response && response.properties) {
                    $("#length").val(response.properties.length);
                    $("#width").val(response.properties.width);
                    $("#height").val(response.properties.height);
                    $("#distance_unit").val(response.properties.distance_unit);
                }
            });
        });

        $(document).ready(function () {
            setDimensionsFieldsState();
        })
    </script>
@endsection
