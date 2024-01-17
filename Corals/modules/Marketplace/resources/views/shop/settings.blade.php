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
                            @foreach($settings as $setting_key => $setting)
                                <li class="nav-item {{ $loop->first ? 'active':'' }}">
                                    <a data-toggle="tab" href="#{{ $setting_key }}"
                                       class="{{ $loop->first ? 'active':'' }} nav-link">{{  $setting_key }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">

                            @foreach($settings as $setting_key => $setting_items)


                                <div id="{{ $setting_key }}"
                                     class="tab-pane {{ $loop->first ? 'active':'' }} ">
                                    <div class="row">
                                        <div class="col-md-10">
                                            {!! CoralsForm::openForm() !!}
                                            @foreach($setting_items as $key => $setting)
                                                @php $setting_concat = 'marketplace_'.strtolower($setting_key).'_'.$key;  @endphp
                                                @php $setting_field = $setting_concat.'|'.$setting['settings_type'];  @endphp
                                                @php $attribues = $setting['attributes'] ?? [] @endphp
                                                @if($setting['type'] == 'text')
                                                    {!! CoralsForm::text($setting_field,$setting['label'],$setting['required'],\Settings::get($setting_concat),$attribues) !!}
                                                @elseif($setting['type'] == 'number')
                                                    {!! CoralsForm::number($setting_field,$setting['label'],$setting['required'],\Settings::get($setting_concat),['step'=>\Arr::get($setting, 'step', 1)]) !!}
                                                @elseif($setting['type'] == 'boolean')
                                                    {!! CoralsForm::boolean($setting_field,$setting['label'],$setting['required'] ?? false, \Settings::get($setting_concat, data_get($setting, 'default', false)),$attribues) !!}
                                                @elseif($setting['type']=='select')
                                                    {!! Form::hidden($setting_field, '') !!}
                                                    {!! CoralsForm::select($setting_field,$setting['label'],is_array( $setting['options']) ?  $setting['options'] : eval($setting['options']), $setting['required'], \Settings::get($setting_concat),$attribues,'select2') !!}
                                                @elseif($setting['type'] == 'textarea')
                                                    {!! CoralsForm::textarea($setting_field,$setting['label'],$setting['required'],\Settings::get($setting_concat ),$attribues) !!}
                                                @endif

                                            @endforeach
                                            {!! CoralsForm::formButtons('<i class="fa fa-save"></i> Save '.$setting_key.' Settings',[],['href'=>url('dashboard')]) !!}

                                            {!! CoralsForm::closeForm() !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

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
