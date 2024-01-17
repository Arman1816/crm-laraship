@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_store_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::openForm($store) !!}
                        <div class="row">
                            <div class="col-md-12">
                                @if($store->hasMedia($store->coverPhotoMediaCollectionName))
                                    <img style="max-height: 300px;overflow: hidden"
                                         src="{{ $store->cover_photo }}" class="img-responsive img-fluid"
                                         style="max-width: 100%;"
                                         alt="@lang('Marketplace::attributes.store.cover_photo')"/>
                                    <br/>
                                    {!! CoralsForm::checkbox('clear_cover_photo', 'Marketplace::attributes.store.clear_cover_photo') !!}
                                @endif
                                {!! CoralsForm::file('cover_photo', 'Marketplace::attributes.store.cover_photo') !!}
                            </div>
                            <!-- place store fields here-->
                            <div class="col-md-6">
                                {!! CoralsForm::text('name','Marketplace::attributes.store.name',true) !!}
                                {!! CoralsForm::text('code','Marketplace::attributes.store.code',false) !!}
                                {!! CoralsForm::text('slug','Marketplace::attributes.store.slug',true) !!}
                                {!! CoralsForm::radio('status','Corals::attributes.status',true,get_array_key_translation(config('marketplace.models.store.statuses')) ) !!}

                                {!! CoralsForm::select('user_id','Marketplace::attributes.store.owner', [], true, null,
                       ['class'=>'select2-ajax','data'=>[
                       'model'=>\Corals\User\Models\User::class,
                       'columns'=> json_encode(['name', 'email']),
                       'selected'=>json_encode($store->user_id?[$store->user_id]:[]),
                       'where'=>json_encode([]),
                       ]],'select2') !!}

                                {!! CoralsForm::number('custom_commission','Marketplace::attributes.store.custom_commission',false,null,['right_addon'=>'<i class="fa fa-percent"></i>','help_text'=> 'Marketplace::attributes.store.custom_commission_help']) !!}


                                {!! \CoralsForm::checkbox('is_featured', 'Marketplace::attributes.store.is_featured', $store->is_featured) !!}

                            </div>
                            <div class="col-md-6">
                                @if($store->hasMedia($store->mediaCollectionName))
                                    <img src="{{ $store->thumbnail }}" class="img-responsive img-fluid"
                                         style="max-width: 100%;"
                                         alt="Thumbnail"/>
                                    <br/>
                                    {!! CoralsForm::checkbox('clear_logo', 'Marketplace::attributes.store.clear') !!}
                                @endif
                                {!! CoralsForm::file('thumbnail', 'Marketplace::attributes.store.logo') !!}
                            </div>

                        </div>

                        {!! CoralsForm::customFields($store) !!}

                        <div class="row">
                            <div class="col-md-12">
                                {!! CoralsForm::formButtons() !!}
                            </div>
                        </div>
                        {!! CoralsForm::closeForm($store) !!}
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
@endsection
