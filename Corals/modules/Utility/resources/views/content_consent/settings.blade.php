@extends('layouts.master')

@section('title', $title_singular)

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('utilities_content_consent') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            @component('components.box',['box_class'=>'box-success'])
                {!! CoralsForm::openForm() !!}
                @foreach($settings as $key => $setting)
                    @php $attributes = $setting['attributes'] ?? [] @endphp

                    @if($setting['type'] == 'text')
                        {!! CoralsForm::text($key,$setting['label'], Str::is('*required*', $setting['validation']),\Settings::get($key, $setting['default']??null),$attributes) !!}
                    @elseif($setting['type'] == 'number')
                        {!! CoralsForm::number($key,$setting['label'], Str::is('*required*', $setting['validation']),\Settings::get($key, $setting['default']??null),array_merge(['step'=>\Arr::get($setting, 'step', 1)],$attributes)) !!}
                    @elseif($setting['type'] == 'boolean')
                        {!! CoralsForm::boolean($key,$setting['label'], Str::is('*required*', $setting['validation']) ?? false, \Settings::get($key, $setting['default']??null),$attributes) !!}
                    @elseif($setting['type']=='select')
                        {!! CoralsForm::select($key,$setting['label'],is_array( $setting['options']) ?  $setting['options'] : eval($setting['options']), Str::is('*required*', $setting['validation']), \Settings::get($key),$attributes) !!}
                    @elseif($setting['type'] == 'textarea')
                        {!! CoralsForm::textarea($key,$setting['label'], Str::is('*required*', $setting['validation']),\Settings::get($key, $setting['default']??null),$attributes) !!}
                    @endif
                @endforeach

                {!! CoralsForm::formButtons('<i class="fa fa-save"></i> Save Settings',[],['href'=>url('dashboard')]) !!}

                {{--                <p>--}}
                {{--                    @lang('Utility::labels.content_consent.accept_action_url')<br/>--}}
                {{--                    {!! generateCopyToClipBoard('accepted-url',url('utilities/content-consent-answer/accepted')) !!}--}}
                {{--                </p>--}}
                {{--                <p>--}}
                {{--                    @lang('Utility::labels.content_consent.reject_action_url')<br/>--}}
                {{--                    {!! generateCopyToClipBoard('rejected-url',url('utilities/content-consent-answer/rejected')) !!}--}}
                {{--                </p>--}}

                {!! CoralsForm::closeForm() !!}
            @endcomponent
        </div>
    </div>
@endsection
