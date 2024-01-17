<div class="row guide-config-fields">
    <div class="col-md-12">
        <hr>
        @if($index >0)
            <a class="brn btn-sm btn-danger d-block pull-right remove-guide-config" style="margin-bottom: 5px" href="#">@lang('Corals::labels.remove')</a>

        @endif
        @foreach($guideConfigFields as $field)

            @php $field['name'] = str_replace('{index}',$index,$field['name']) @endphp

            @isset($guideConfigValue)
                @php($value = $guideConfig[$field['name']] ?? null)
            @endisset
            {!! CoralsForm::handleCustomFieldInput($field,isset($value) ? $value : null) !!}
        @endforeach
    </div>
</div>