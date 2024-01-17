@extends('layouts.crud.create_edit')

@section('css')
    {!! \Html::style('assets/corals/plugins/nestable/select2totree.css') !!}
@endsection

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_category_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm($category, ['files'=>true]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::text('name','Marketplace::attributes.category.name',true) !!}
                        {!! CoralsForm::text('slug','Marketplace::attributes.category.slug',true) !!}
                        {!! CoralsForm::text('external_id','Marketplace::attributes.category.external_id',false) !!}
                        {!! CoralsForm::select('category_attributes[]','Marketplace::attributes.category.filter_attributes', \Marketplace::getAttributesList(),
                        false, $category->categoryAttributes()->pluck('attribute_id'),
                         ['multiple'=>true,'help_text'=>'Marketplace::attributes.category.filter_attributes_help'], 'select2') !!}

                        {!! CoralsForm::textarea('description','Marketplace::attributes.category.description', false) !!}
                    </div>
                    <div class="col-md-6">
                        {!! CoralsForm::radio('status','Corals::attributes.status',true, trans('Corals::attributes.status_options')) !!}

                        {!! CoralsForm::select('parent_id', 'Marketplace::attributes.category.parent_cat',
                         \Marketplace::getCategoriesList($category->exists?$category->parent_id:[], $category->exists?$category->id:[]),
                         false, null, [], 'select2-tree') !!}

                        {!! CoralsForm::checkbox('is_featured', 'Marketplace::attributes.category.is_featured', $category->is_featured) !!}
                        @if($category->hasMedia($category->mediaCollectionName))
                            <img src="{{ $category->thumbnail }}" class="img-responsive img-fluid"
                                 style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            {!! CoralsForm::checkbox('clear', 'Marketplace::attributes.category.clear') !!}
                        @endif
                        {!! CoralsForm::file('thumbnail', 'Marketplace::attributes.category.thumbnail') !!}
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

@section('js')
    {!! \Html::script('assets/corals/plugins/nestable/select2totree.js') !!}
@endsection