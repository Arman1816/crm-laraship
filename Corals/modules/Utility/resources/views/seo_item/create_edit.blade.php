@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('utility_seo_item') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($seo_item) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('slug','Utility::attributes.seo_item.slug',true,$seo_item->slug) !!}
                        {!! CoralsForm::select('route','Utility::attributes.seo_item.route', \SEOItems::getRouteManager(), true, null, [], 'select2') !!}
                        {!! CoralsForm::text('title','Utility::attributes.seo_item.title') !!}
                        {!! CoralsForm::text('type','Utility::attributes.seo_item.type') !!}
                    </div>
                    <div class="col-md-6">
                        {!! CoralsForm::textarea('meta_description','Utility::attributes.seo_item.meta_description', false, $seo_item->meta_description, ['rows'=>4]) !!}
                        {!! CoralsForm::textarea('meta_keywords','Utility::attributes.seo_item.meta_keywords', false, $seo_item->meta_keywords, ['rows'=>4]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @if($seo_item->hasMedia($seo_item->mediaCollectionName))
                            <img src="{{ $seo_item->image }}" class="img-responsive" style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            {!! CoralsForm::checkbox('clear', 'Utility::attributes.seo_item.clear') !!}
                        @endif
                        {!! CoralsForm::file('image', 'Utility::attributes.seo_item.image') !!}
                    </div>
                </div>

                {!! CoralsForm::customFields($seo_item, 'col-md-6') !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>

                {!! CoralsForm::closeForm($seo_item) !!}
            @endcomponent
        </div>
    </div>
@endsection
