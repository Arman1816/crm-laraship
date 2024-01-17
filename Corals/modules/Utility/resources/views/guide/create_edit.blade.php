@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('utility_guide_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($guide) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('url','Utility::attributes.guide.url',true) !!}
                        {!! CoralsForm::select('route','Utility::attributes.guide.route', \SEOItems::getRouteManager(), true, null, [], 'select2') !!}

                    </div>
                    <div class="col-md-6">
                        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options'),'active') !!}
                    </div>
                </div>

                <hr>

                <div id="guides-config-wrapper">

                    <div class="row">
                        <div class="col-md-6">
                            <h4>@lang('Utility::labels.guide.guide_config')</h4>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-success pull-right" id="add-new-guide-config">
                                 @lang('Utility::labels.guide.add')
                            </a>
                        </div>
                    </div>

                    @forelse($guide->getProperty('guide_config') ?? [] as $index => $guideConfig)
                        @include('Utility::guide.partials.guide_config_fields',compact('index','guideConfig'))
                    @empty
                        @include('Utility::guide.partials.guide_config_fields',['index'=>0])
                    @endforelse
                </div>


                {!! CoralsForm::customFields($guide, 'col-md-6') !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>

                {!! CoralsForm::closeForm($guide) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')

    <script>
        $('#add-new-guide-config').on('click', function () {
            let index = $('.guide-config-fields').length;

            let url = '{{url('utilities/guides/get-config-guide-fields')}}' + `/${index}`;
            $.get(url, (guideConfigFields) => {
                $('#guides-config-wrapper .row:last').after(guideConfigFields);
            });
        });

        $(document).on('click', '.remove-guide-config', function (e) {
            e.preventDefault();

            $(this).closest('.guide-config-fields').slideUp(500, function () {
                $(this).remove();
            });
        });
    </script>
@endsection
