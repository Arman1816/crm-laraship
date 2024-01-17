<div class="row">
    <div class="col-md-12">
        {!! Form::open(['url'=>url('marketplace/products/sku/bulk-action'),'class'=>'ajax-form','data-page_action'=>'updatedSuccessfully']) !!}

        {!! CoralsForm::number('regular_price','Marketplace::attributes.sku.regular_price', false, null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="fa fa-dollar"></i>']) !!}

        {!! CoralsForm::number('sale_price','Marketplace::attributes.sku.sale_price', false,null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="fa fa-dollar"></i>']) !!}

        {!! CoralsForm::select('inventory', 'Marketplace::attributes.sku.inventory', get_array_key_translation(config('marketplace.models.sku.inventory_options')), false, '', ['class'=>'inventory-select']) !!}

        <div id="inventory-value">
        </div>

        <div class="form-group">
            <label>
                @lang('Marketplace::attributes.sku.image')
            </label>
            {!! Form::file('image') !!}
        </div>

        {{--        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options'), 'active') !!}--}}

        {!! Form::hidden('action','updateVariations') !!}

        {!! Form::hidden('selection',null,['id'=>'sku_selections_ids']) !!}

        {!! CoralsForm::formButtons(trans('Corals::labels.update'),[],['show_cancel'=>false]) !!}

        {!! Form::close() !!}
    </div>
</div>
<script>

    checked_ids = $('#SKUDataTable tbody input:checkbox:checked').map(function () {
        return $(this).val();
    }).get();

    $('#sku_selections_ids').val(JSON.stringify(checked_ids));

    function updatedSuccessfully(r, f) {
        closeModal(r, f);
        $('#SKUDataTable').DataTable().ajax.reload();
    }

    function setInventoryValue(element) {
        let selectedValue = element.val(),
            input = '';

        if (selectedValue === 'bucket') {
            input = '{{ CoralsForm::select('inventory_value_name','Marketplace::attributes.sku.inventory_value', get_array_key_translation(config('marketplace.models.sku.bucket')), false, null,['id'=>'inventory_value-sku_id']) }}';
        } else if (selectedValue === 'finite') {
            input = '{{ CoralsForm::number('inventory_value_name','Marketplace::attributes.sku.inventory_value',false,null,['step'=>1,'min'=>0,'max'=>999999,'id'=>'inventory_value-sku_id']) }}';
        } else {
            input = '';
        }


        input = input.replaceAll('inventory_value_name', 'inventory_value');


        $('#inventory-value').html(input);

    }

    $(document).ready(function () {
        let inventorySelect = $('.inventory-select');


        inventorySelect.change(function (event) {
            let el = $(this);

            setInventoryValue(el);
        });

        inventorySelect.trigger('change');
    });

</script>
