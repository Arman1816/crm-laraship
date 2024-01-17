@extends('layouts.master')

@section('title', $title_singular)

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_settings') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        @if(count($settings))
            <div class="col-md-10">
                @component('components.box',['box_class'=>'box-success'])
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="nav-item active ">
                                <a data-toggle="tab" href="#store-details"
                                   class="active nav-link">@lang('Marketplace::labels.store.store_details')</a>
                            </li>
                            @foreach($settings as $setting_key => $setting)
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#{{ $setting_key }}"
                                       class=" nav-link">{{  $setting_key }}</a>
                                </li>
                            @endforeach
                            @if($connectGateways)
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#connect-accounts"
                                       class="nav-link">
                                        @lang('Marketplace::labels.connect_accounts.title')
                                    </a>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            <div id="store-details"
                                 class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach(user()->stores as $store)
                                            @component('components.box')
                                                {!! CoralsForm::openForm($store,['url' => url(config('marketplace.models.store.resource_url').'/'.$store->hashed_id),'method'=>'PUT','files'=>true]) !!}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @php $store_url = $store->getUrl() ; @endphp
                                                        <div class="alert alert-primary">
                                                            <h5>{!!    trans('Marketplace::labels.store.store_url',['url'=>$store_url ])!!}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-b-20">
                                                    <!-- place store fields here-->
                                                    <div class="col-md-12">
                                                        @if($store->hasMedia($store->coverPhotoMediaCollectionName))
                                                            <img style="max-height: 300px;overflow: hidden"
                                                                 src="{{ $store->cover_photo }}"
                                                                 class="img-responsive img-fluid"
                                                                 style="max-width: 100%;"
                                                                 alt="@lang('Marketplace::attributes.store.cover_photo')"/>
                                                            <br/>
                                                            {!! CoralsForm::checkbox('clear_cover_photo', 'Marketplace::attributes.store.clear') !!}
                                                        @endif
                                                        {!! CoralsForm::file('cover_photo', 'Marketplace::attributes.store.cover_photo') !!}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- place store fields here-->
                                                    <div class="col-md-8">

                                                        {!! CoralsForm::text('name','Marketplace::attributes.store.name',true,null,['id'=>'store-name']) !!}
                                                        {!! CoralsForm::text('short_description','Marketplace::attributes.store.short_description',true,null,[]) !!}
                                                        {!! CoralsForm::textarea('return_policy','Marketplace::attributes.store.return_policy', false, null, ['class'=>'ckeditor-simple','rows'=>5]) !!}



                                                        @if(  \Settings::get('marketplace_general_enable_domain_parking', false))
                                                            {!! CoralsForm::text('parking_domain','Marketplace::attributes.store.parking_domain',false) !!}
                                                        @endif
                                                    </div>

                                                    <div class="col-md-4">
                                                        @if($store->hasMedia($store->mediaCollectionName))
                                                            <img src="{{ $store->thumbnail }}"
                                                                 class="img-responsive img-fluid"
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
                                                        {!! CoralsForm::formButtons(trans('Marketplace::labels.store.save_store_details'), [], []) !!}

                                                    </div>
                                                </div>
                                                {!! CoralsForm::closeForm($store) !!}
                                            @endcomponent
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @foreach($settings as $setting_key => $setting_items)
                                <div id="{{ $setting_key }}"
                                     class="tab-pane">
                                    <div class="row">
                                        <div class="col-md-10">
                                            {!! CoralsForm::openForm() !!}
                                            @foreach($setting_items as $key => $setting)
                                                @php $setting_concat = 'marketplace_'.strtolower($setting_key).'_'.$key;  @endphp
                                                @php $setting_field = $setting_concat.'|'.$setting['cast_type'];  @endphp

                                                @if($setting['type'] == 'text')
                                                    {!! CoralsForm::text($setting_field,$setting['label'],$setting['required'],$store->getSettingValue($setting_concat )) !!}
                                                @elseif($setting['type'] == 'textarea')

                                                    {!! CoralsForm::textarea($setting_field,$setting['label'],$setting['required'],$store->getSettingValue($setting_concat )) !!}
                                                @elseif($setting['type'] == 'number')
                                                    {!! CoralsForm::number($setting_field,$setting['label'],$setting['required'],$store->getSettingValue($setting_concat),['step'=>\Arr::get($setting, 'step', 1)]) !!}
                                                @elseif($setting['type'] == 'boolean')
                                                    {!! CoralsForm::boolean($setting_field,$setting['label'],false, $store->getSettingValue($setting_concat, 'false')) !!}
                                                @elseif($setting['type']=='select')
                                                    {!! Form::hidden($setting_field, '') !!}
                                                    {!! CoralsForm::select(
                                                            $setting_field,
                                                            $setting['label'],
                                                            is_array( $setting['options']) ?  $setting['options'] : eval($setting['options']),
                                                            $setting['required'],
                                                            $store->getSettingValue($setting_concat),
                                                            $setting['attributes']??[],
                                                            'select2'
                                                        ) !!}
                                                @endif
                                            @endforeach
                                            {!! CoralsForm::formButtons('<i class="fa fa-save"></i> Save '.$setting_key.' Settings',[],['href'=>url('dashboard')]) !!}

                                            {!! CoralsForm::closeForm() !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if($connectGateways)
                                <div id="connect-accounts" class="tab-pane">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @foreach($connectGateways as $connect)
                                                @if(data_get($connect,'connect_settings'))
                                                    {!! data_get($connect,'connect_settings') !!}
                                                @else
                                                    <div class="py-2">
                                                        {{ $connect['gateway_title'] }}
                                                        @if($connect['status'] === 'PAYOUTS_ENABLED')
                                                            {!! formatStatusAsLabels($connect['status'],['level'=>'success']) !!}
                                                        @elseif($connect['status'])
                                                            {!! formatStatusAsLabels($connect['status'],['level'=>'warning']) !!}
                                                        @endif
                                                    </div>
                                                    @if(!$connect['status'] || $connect['status'] !== 'PAYOUTS_ENABLED')
                                                        <p class="alert alert-info">
                                                            @lang('Marketplace::labels.connect_accounts.not_connected')
                                                            @if($connect['account_id'] && $connect['gateway'])
                                                                <a href="{{ url('marketplace/store/settings/check-connect-account/'.$connect['gateway'].'/'.$connect['account_id']) }}"
                                                                   class="btn btn-primary" data-action="get"
                                                                   data-page_action="site_reload">
                                                                    @lang('Marketplace::labels.connect_accounts.recheck_status')
                                                                </a>
                                                            @endif
                                                        </p>
                                                        <a href="{{ $connect['connect_url'] }}" class="btn btn-success"
                                                           target="_blank">
                                                            @lang('Marketplace::labels.connect_accounts.connect_url', ['gateway' => $connect['gateway_title']])
                                                        </a>
                                                    @endif
                                                @endif
                                                <hr/>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endcomponent
            </div>
        @else
            <div class="col-md-4">
                <div class="alert alert-warning">
                    <h4>@lang('Marketplace::labels.shop.no_setting_found')</h4>
                </div>
            </div>
        @endif
    </div>
@endsection
