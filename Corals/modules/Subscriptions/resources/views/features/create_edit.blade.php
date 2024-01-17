@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('feature_create_edit',$product) }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($feature) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','Subscriptions::attributes.feature.name',true) !!}
                        {!! CoralsForm::text('caption','Subscriptions::attributes.feature.caption',true) !!}
                        {!! CoralsForm::radio('status','Corals::attributes.status',true, trans('Corals::attributes.status_options')) !!}
                        {!! CoralsForm::select('type', 'Subscriptions::attributes.feature.type', trans('Subscriptions::attributes.feature.type_option'), true) !!}
                        <div id="extra-fields" style="display: none;">
                            {!! CoralsForm::select('extras[config][source]', 'Subscriptions::attributes.feature.extras.source', get_array_key_translation(config('subscriptions.models.feature.sources_list')), true) !!}
                            {!! CoralsForm::text('extras[config][code]','Subscriptions::attributes.feature.extras.code',true) !!}
                        </div>
                        {!! CoralsForm::text('unit','Subscriptions::attributes.feature.unit') !!}
                        {!! CoralsForm::text('limit_reached_message','Subscriptions::attributes.feature.limit_reached_message') !!}
                        {!! CoralsForm::select('feature_model', 'Subscriptions::attributes.feature.model', \SubscriptionProducts::getFeatureModels(), false, $feature->feature_model,['help_text'=>'Subscriptions::attributes.feature.model_help'],'select2') !!}
                    </div>

                    <div class="col-md-6">
                        {!! CoralsForm::select('related_urls[]','Subscriptions::attributes.feature.related_urls', $feature->related_urls?array_combine($feature->related_urls,$feature->related_urls):[] ,false, $feature->related_urls,['class'=>'tags', 'multiple'=>true], 'select2') !!}

                        {!! CoralsForm::textarea('description','Subscriptions::attributes.feature.description', true) !!}

                        {!! CoralsForm::checkbox('is_visible', 'Subscriptions::attributes.feature.is_visible',$feature->exists?$feature->is_visible:true, 1,
                        ['help_text'=>'Subscriptions::attributes.feature.visible_help']) !!}

                        {!! CoralsForm::checkbox('per_cycle', 'Subscriptions::attributes.feature.per_cycle',$feature->exists?$feature->per_cycle:false, 1,
                        ['help_text'=>'Subscriptions::attributes.feature.per_cycle_help']) !!}

                        {!! CoralsForm::customFields($feature,'col-md-12') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($feature) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#type').on('change', function () {
            showExtraFieldsDependOnSelectedType();
        });

        $(showExtraFieldsDependOnSelectedType = () => {
            let type = $('#type').val();

            if (type === 'config') {
                $('#extra-fields').slideDown();
            } else {
                $('#extra-fields').slideUp();
            }
        });
    </script>
@endsection
