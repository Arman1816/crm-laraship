<style type="text/css">
    .select-section button {
        padding: 6px 12px !important;
    }

    .select-section .form-group {
        margin-bottom: 0;
    }
</style>

<div class="select-section {{ $class = \Str::random().'_setting' }}">
    {!! CoralsForm::select('properties[display_type]','Marketplace::labels.attribute.display_type',
                  get_array_key_translation(config('settings.models.custom_field_setting.select_display_type_options')),true,$displayType,
                  ['id'=>'display_option_type','readonly'=> $attribute->exists])
                  !!}
    <div class="table-responsive">
        <table id="values-table" width="100%"
               class="table color-table info-table table table-hover table-striped table-condensed">
            <thead>
            <tr>
                <th width="15%">@lang('Marketplace::labels.attribute.order')</th>
                <th width="35%">@lang('Marketplace::labels.attribute.value')</th>
                <th width="50%">@lang('Marketplace::labels.attribute.display')</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($options as $option)
                @include('Marketplace::attributes.partials.select_options',['index'=> $loop->index,'name'=>$name,'optional'=>$option,'displayType'=>$displayType])
            @endforeach
            </tbody>
        </table>
    </div>
    <button type="button" class="btn btn-success btn-sm" id="add-value"><i
                class="fa fa-plus"></i>
    </button>
    <span class="help-block">
                            @lang('Marketplace::labels.attribute.add_new_option')
                        </span>
</div>

<script type="text/javascript">
    {{ $name}}_init = function () {
        if ($(".{{ $class }} #values-table").length > 0) {
            $(document).on('click', '.{{ $class }} #add-value', function () {
                var index = $('.{{ $class }} #values-table tr:last').data('index');
                if (isNaN(index)) {
                    index = 0;
                } else {
                    index++;
                }

                let displayType = $('#display_option_type').val();

                $.get(`{{url('marketplace/attributes/render-select-options')}}?index=${index}&name={{$name}}&display_type=${displayType}`, (renderedInputs) => {
                    $('.{{ $class }} #values-table tr:last').after(renderedInputs);
                    $('#display_option_type').attr('readonly', true);

                });
            });

            $(document).on('click', '.remove-value', function () {
                var index = $(this).data('index');
                $("#tr_" + index).remove();
                selectOptionTypeReadonlyHandler();

            });
        }

        function selectOptionTypeReadonlyHandler() {

            if ($('.{{ $class }} #values-table tbody tr').length <= 0) {
                $('#display_option_type').attr('readonly', false);
            } else {
                $('#display_option_type').attr('readonly', true);
            }
        }

        selectOptionTypeReadonlyHandler();
    };

    window.initFunctions.push('{{ $name}}_init');
</script>
