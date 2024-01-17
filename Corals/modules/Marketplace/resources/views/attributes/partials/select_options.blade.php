<tr id="tr_{{$index}}" data-index="{{$index}}">
    @isset($option)
        <input name="{{ $name."[$loop->index][id]" }}" type="hidden"
               value="{{ $option->id }}" class="form-control"/>
    @endisset

    <td>
        <div class="form-group">
            <input name="{{ $name }}[{{$index}}][option_order]" type="text"
                   value="{{ data_get($option ?? [],'option_order','')}}" class="form-control"/>
        </div>
    </td>
    <td>
        <div class="form-group">
            <input name="{{ $name }}[{{$index}}][option_value]" type="text"
                   value="{{data_get($option ?? [],'option_value','') ?? ''}}" class="form-control"/>
        </div>
    </td>

    <td>

        @switch($displayType)
            @case('label')
            {!! CoralsForm::text(sprintf("%s[%s][option_display]",$name,$index),'',false,data_get($option ?? [],'option_display','')) !!}
            @break
            @case('color')
            {!! CoralsForm::color(sprintf("%s[%s][option_display]",$name,$index),'',false,data_get($option ?? [],'option_display','')) !!}
            @break
            @case('image')
            {!! CoralsForm::file(sprintf("%s[%s][option_display]",$name,$index),'',false,['id'=>"file__$index"]) !!}
            @if(optional($option ?? [])->exists)
                <img src="{{$option->media()->first()->getFullUrl()}}" style="max-width: 150px;max-height: 150px"
                     alt="img">
            @endif
            @break
        @endswitch

    </td>

        <td>
            <div class="form-group">
                <button type="button" class="btn btn-danger btn-sm remove-value" style="margin:0;"
                        data-index="{{$index}}">
                    <i class="fa fa-remove"></i></button>
            </div>
        </td>

</tr>
