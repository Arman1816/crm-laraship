@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('utility_category_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($category) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','Utility::attributes.category.name',true) !!}
                        {!! CoralsForm::text('slug','Utility::attributes.category.slug',true) !!}
                        {!! CoralsForm::radio('status','Corals::attributes.status',true, trans('Corals::attributes.status_options')) !!}
                        {!! CoralsForm::select('module','Utility::attributes.tag.module', \Utility::getUtilityModules()) !!}
                        {!! CoralsForm::select('parent_id', 'Utility::attributes.category.parent_cat', \Category::getCategoriesList(null,true, false, null, $category->exists?[$category->id]:[]), false, null, [], 'select2') !!}
                        {!! CoralsForm::checkbox('is_featured', 'Utility::attributes.category.is_featured', $category->is_featured) !!}
                        {!! CoralsForm::select('category_attributes[]','Utility::attributes.category.attributes', \Category::getAttributesList(),
                        false, $category->categoryAttributes()->pluck('attribute_id'), ['multiple'=>true], 'select2') !!}
                    </div>
                    <div class="col-md-6">
                        @php
                            if($category->hasMedia($category->mediaCollectionName)){
                                $media =  $category->thumbnail;
                                $hasMedia = true;
                            }else{
                                $media =   $category->getProperty('thumbnail_link');
                            }
                        @endphp

                        @if($media)
                            <img src="{{ $media }}" class="img-responsive" style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            @if(isset($hasMedia))
                                {!! CoralsForm::checkbox('clear', 'Utility::attributes.category.clear') !!}
                            @endif
                        @endif
                        {!! CoralsForm::file('thumbnail', 'Utility::attributes.category.thumbnail') !!}

                        @if(!isset($hasMedia))
                            {!! CoralsForm::text('properties[thumbnail_link]','Utility::attributes.category.thumbnail_link', false) !!}
                        @endif
                        {!! CoralsForm::textarea('description','Utility::attributes.category.description') !!}
                    </div>
                </div>

                {!! CoralsForm::customFields($category, 'col-md-6') !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>

                {!! CoralsForm::closeForm($category) !!}
            @endcomponent
        </div>
    </div>
@endsection
