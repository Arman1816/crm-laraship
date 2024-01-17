@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('tag_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($tag) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','Utility::attributes.tag.name',true) !!}

                        {!! CoralsForm::text('slug','Utility::attributes.tag.slug',true) !!}

                        {!! CoralsForm::text('properties[icon]','Utility::attributes.tag.icon',false) !!}

                        {!! CoralsForm::select('module','Utility::attributes.tag.module', \Utility::getUtilityModules()) !!}

                        {!! CoralsForm::radio('status','Corals::attributes.status',true, trans('Corals::attributes.status_options')) !!}

                        {!! CoralsForm::customFields($tag, 'col-md-12') !!}
                    </div>
                    <div class="col-md-6">

                        @if($tag->hasMedia($tag->mediaCollectionName))
                            <img src="{{ $tag->thumbnail }}" class="img-responsive" style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            {!! CoralsForm::checkbox('clear', 'Utility::attributes.tag.clear') !!}
                        @endif
                        {!! CoralsForm::file('thumbnail', 'Utility::attributes.tag.thumbnail') !!}
                    </div>
                </div>

                {!! CoralsForm::formButtons() !!}

                {!! CoralsForm::closeForm($tag) !!}
            @endcomponent
        </div>
    </div>
@endsection
