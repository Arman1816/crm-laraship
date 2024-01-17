@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('download_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            {!! CoralsForm::openForm($download) !!}
            @component('components.box')
                <div class="row">
                    <div class="col-md-8">
                        {!! CoralsForm::text('title','CMS::attributes.content.title',true) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::textarea('content','CMS::attributes.content.content',true, null, ['class'=>'ckeditor']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::checkbox('published', 'CMS::attributes.content.published',$download->published) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('CMS::download.partials.downloadable', ['model' => $download])
                    </div>
                </div>
                {!! CoralsForm::customFields($download) !!}
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
            @endcomponent
            {!! CoralsForm::closeForm($download) !!}
        </div>
    </div>
@endsection
