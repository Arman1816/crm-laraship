<div class="row">
    <div class="col-md-12">
        {!! Form::open(['url'=>isset($bulk) ? url('marketplace/products/bulk-action') : $product->getOriginalShowURL().'/copy-product','class'=>'ajax-form','data-page_action'=> isset($bulk)?'copyRequestSent':'closeModal']) !!}

        @isset($bulk)
            {!! Form::hidden('action','clone_products') !!}
            {!! Form::hidden('selection',null,['id'=>'products_selections_ids']) !!}
        @endisset

        {!! \CoralsForm::select('store_id', 'Marketplace::attributes.product.store', [], true, null,
                        [
                            'class' => 'select2-ajax',
                            'data' => [
                                'model' => \Corals\Modules\Marketplace\Models\Store::class,
                                'columns' => json_encode(['name']),
                                'selected' => json_encode( []),
                                 'where' => json_encode(isset($bulk) ? []: [['field' => 'id', 'operation' => '<>', 'value' =>  $product->store_id]]),
                            ]
                        ], 'select2'); !!}

        {!! CoralsForm::checkbox('copy_with_media','Marketplace::labels.product.clone_product_with_media') !!}
        {!! CoralsForm::formButtons(trans('Marketplace::labels.product.clone_product'),[],['show_cancel'=>false]) !!}


        {!! Form::close() !!}
    </div>
</div>

<script>
    initSelect2ajax();

    @isset($bulk)
        checkedIds = $('#ProductsDataTable tbody input:checkbox:checked').map(function () {
        return $(this).val();
    }).get();

    $('#products_selections_ids').val(JSON.stringify(checkedIds));

    function copyRequestSent(r, f) {
        closeModal(r, f);
        $('#ProductsDataTable').DataTable().ajax.reload();
    }

    @endisset
</script>

