@extends('layouts.crud.create_edit')

@section('css')
    {!! \Html::style('assets/corals/plugins/nestable/select2totree.css') !!}
    <style>
        .location-rate:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .location-rate {
            padding: 10px;
        }

        .dimensions-hint {
            padding: 3px 5px;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
@endsection

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_product_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-7">
            @component('components.box')
                {!! CoralsForm::openForm($product) !!}
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::text('name','Marketplace::attributes.product.name',true,$product->name,[]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::textarea('caption','Marketplace::attributes.product.caption',true,$product->caption,['help_text'=>'']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::select('attribute_sets[]', 'Marketplace::attributes.attribute_set.sets',
                            \Marketplace::getAttributeSetsList(), false,
                            \Marketplace::getProductAttributeSets($product),
                            ['multiple'=>true,'id'=>'attribute_sets'], 'select2') !!}
                        <div id="attribute_sets_attributes">
                        </div>

                        {!! CoralsForm::select('type','Marketplace::attributes.product.type',trans('Marketplace::attributes.product.type_option') ,true, null,['class'=>'']) !!}
                        <div class="simple_product_attributes hidden">
                            {!! CoralsForm::text('code','Marketplace::attributes.product.sku_code',true,$product->exists? $sku->code:'' ,[] ) !!}
                            {!! CoralsForm::number('regular_price','Marketplace::attributes.product.regular_price',true,$product->exists? $sku->regular_price:null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                            {!! CoralsForm::number('sale_price','Marketplace::attributes.product.sale_price',false,$product->exists? $sku->sale_price:null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>']) !!}
                            {!! CoralsForm::number('allowed_quantity','Marketplace::attributes.product.allowed_quantity', false,$sku->exists?$sku->allowed_quantity:0,
                            ['step'=>1,'min'=>0,'max'=>999999, 'help_text'=>'Marketplace::attributes.product.help']) !!}
                            {!! CoralsForm::select('inventory','Marketplace::attributes.product.inventory',  get_array_key_translation(config('marketplace.models.sku.inventory_options')),true,$sku->inventory) !!}
                            <div id="inventory_value_wrapper"></div>
                        </div>
                        <div class="variable_product_attributes hidden">
                            {!! CoralsForm::text('product_code','Marketplace::attributes.product.product_code',true) !!}
                            {!! CoralsForm::select('properties[show_skus_as]','Marketplace::attributes.product.show_skus_as',
                                trans('Marketplace::attributes.product.show_skus_as_options'), false, null, [], 'select2') !!}

                            {!! CoralsForm::select('variation_options[]','Marketplace::attributes.product.variation_options',
                                [], true, null, ['multiple'=>true,'id'=>'variation_options'], 'select2') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! CoralsForm::select('brand_id','Marketplace::attributes.product.brand', \Marketplace::getBrandsList(),false,null,[], 'select2') !!}
                        {!! CoralsForm::radio('status','Corals::attributes.status',true, trans('Corals::attributes.status_options')) !!}
                        {!! CoralsForm::select('categories[]','Marketplace::attributes.product.categories', \Marketplace::getCategoriesList($product->exists?$product->categories()->pluck('id')->toArray():[]), true, null,['multiple'=>true,'id'=>'categories'], 'select2-tree') !!}
                        {!! CoralsForm::select('tax_classes[]','Marketplace::attributes.product.tax_classes', \Payments::getTaxClassesList(), false, null,['multiple'=>true], 'select2') !!}
                        {!! CoralsForm::text('demo_url','Marketplace::attributes.product.demo_url',false,$product->exists? $product->demo_url:'' ,[] ) !!}

                        {!! \Store::getStoreFields($product) !!}

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::checkbox('price_per_classification', 'Marketplace::attributes.product.price_per_classification', $hasClassificationPrices = ($product->exists && array_filter($product->classification_price??[])) , 1,['help_text'=>'Marketplace::attributes.product.help_classification_price']) !!}
                        <div id="classification_price_section"
                             style="display: {{ $hasClassificationPrices ? "block":"none" }}">
                            <div class="table-responsive">
                                <table class="table color-table info-table table table-hover table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        @foreach(\Settings::get('customer_classifications',[]) as $key=>$value)
                                            <th class="text-center">{{ $value }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach(\Settings::get('customer_classifications',[]) as $key=>$value)
                                            <td>
                                                {!! CoralsForm::number("classification_price[$key]",'',false,null,['step'=>0.01,'min'=>0,'max'=>999999,'left_addon'=>'<i class="'.$sku->currency_icon.'"></i>'] ) !!}
                                            </td>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        {!! CoralsForm::radio('bulk_price','Marketplace::labels.product.bulk_price',false,get_array_key_translation(config('marketplace.models.product.bulk_price_options')),\Marketplace::bulkPriceType($product)) !!}


                        @include('Marketplace::products.partials.price_per_quantity_section')

                        @include('Marketplace::products.partials.offers_section')

                        {!! CoralsForm::textarea('description','Marketplace::attributes.product.description',false, $product->description, ['class'=>'ckeditor','rows'=>5]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{--                        {!! CoralsForm::checkbox('shipping[enabled]', 'Marketplace::attributes.product.shippable', $product->exists ?  data_get($product->shipping,'enabled') : 0) !!}--}}
                        {!! CoralsForm::radio('shipping[enabled]','Marketplace::attributes.product.shippable', true, trans('Marketplace::attributes.product.product_shipping_enabled'), $product->exists ?  data_get($product->shipping,'enabled') : 0) !!}
                        <div id="shipping"
                             style="{{ $product->exists ? (!data_get($product->shipping,'enabled')?'display:none':'') : 'display:none' }}">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! CoralsForm::radio('shipping[shipping_option]','Marketplace::attributes.product.shipping_option', true, trans('Marketplace::labels.package.product_shipping_options'), data_get($product->shipping,'shipping_option', 'calculate_rates')) !!}
                                </div>
                            </div>
                            <div id="calculate_rates" class="shipping-options"
                                 style="{{ data_get($product->shipping,'shipping_option', 'calculate_rates') ==='calculate_rates'?'':'display:none' }}">
                                @include('Marketplace::shippings.partials.shipping_rules_list', compact('product'))
                            </div>
                            <div id="flat_rate_prices" class="shipping-options"
                                 style="{{ data_get($product->shipping,'shipping_option') ==='flat_rate_prices'?'':'display:none' }}">
                                @include('Marketplace::shippings.partials.flat_rate_prices', compact('product'))
                            </div>
                            <hr/>
                            <div class="">
                                <small class="alert alert-warning dimensions-hint">
                                    @lang('Marketplace::labels.shipping.dimensions_needed')
                                </small>
                                <div class="row">
                                    <div class="col-md-3">
                                        {!! CoralsForm::number('shipping[width]','Marketplace::attributes.product.width',false,null,['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','in'),'min'=>0]) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! CoralsForm::number('shipping[height]','Marketplace::attributes.product.height',false,null,['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','in'),'min'=>0]) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! CoralsForm::number('shipping[length]','Marketplace::attributes.product.length',false,null,['help_text'=>\Settings::get('marketplace_shipping_dimensions_unit','in'),'min'=>0]) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! CoralsForm::number('shipping[weight]','Marketplace::attributes.product.weight',false,null,['help_text'=>\Settings::get('marketplace_shipping_weight_unit','oz'),'min'=>0]) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! CoralsForm::select('shipping[package_id]', 'Marketplace::attributes.product.package', \ShippingPackages::getAvailablePackages(), false, null, ['id'=>'package_id'],'select2') !!}
                                    </div>
                                </div>
                            </div>
                            <hr/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::checkbox('external', 'Marketplace::attributes.product.external', $product->external_url, 1,['onchange'=>"toggleExternalURL();",'help_text'=>'Marketplace::attributes.product.help_external']) !!}
                        <div id="external_section" style="display: {{ $product->external_url ? "block":"none" }}">
                            {!! CoralsForm::text('external_url','Marketplace::attributes.product.external_url',false,null) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::checkbox('downloads_enabled', 'Marketplace::attributes.product.downloads_enabled', count($product->downloads), 1,['onchange'=>"toggleDownloadable();"]) !!}
                        @include('Marketplace::products.partials.downloadable', ['model' => $product])
                    </div>
                </div>

                @if (\Store::isStoreAdmin())
                    <div class="row">
                        <div class="col-md-6">
                            {!! \CoralsForm::checkbox('is_featured', 'Marketplace::attributes.product.is_featured', $product->is_featured) !!}
                            {!! \CoralsForm::text('slug', 'Marketplace::attributes.product.slug', false, $product->slug, ['help_text' => 'Marketplace::attributes.product.slug_help']) !!}
                            {!! CoralsForm::select('tags[]','Marketplace::attributes.product.tags', \Marketplace::getTagsList(),false,null,['class'=>'tags','multiple'=>true], 'select2') !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            {!! CoralsForm::checkbox('private_content_pages', 'Marketplace::attributes.product.private_content_page', count($product->posts), 1,['onchange'=>"togglePremuimContent();"]) !!}
                        </div>
                    </div>
                    <div class="row" id="product_pages" style="display: {{ count($product->posts) ? "block":"none" }}">
                        <div class="col-md-12">
                            {!! CoralsForm::select('posts[]','Marketplace::attributes.product.posts', [], false, null,
                            ['class'=>'select2-ajax','multiple'=>"multiple",'data'=>[
                            'model'=>\Corals\Modules\CMS\Models\Content::class,
                            'columns'=> json_encode(['title']),
                            'selected'=>json_encode($product->posts()->pluck('posts.id')->toArray()),
                            'where'=>json_encode([['field'=>'private','operation'=>'=','value'=>1]]),
                            ]],'select2') !!}
                        </div>
                    </div>
                @endif

                {!! \Actions::do_action('marketplace_product_form_post_fields', $product) !!}

                {!! CoralsForm::customFields($product) !!}
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>

                <input name="gallery_new" id="gallery_new" type="hidden">
                <input name="gallery_deleted" id="gallery_deleted" type="hidden">
                <input name="gallery_favorite" id="gallery_favorite" type="hidden">

                {!! CoralsForm::closeForm($product) !!}
            @endcomponent
        </div>
        <div class="col-md-5">
            @component('components.box')
                @include('Utility::gallery.gallery',['galleryModel'=> $product, 'editable'=>true])
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script>
        var variationLoadValues = @json($product->variationOptions->pluck('id')->toArray());
    </script>

    @include('Marketplace::attribute_sets.partials.attribute_set_scripts', [
       'attribute_set_id' => '#attribute_sets',
       'set_attributes_div' => '#attribute_sets_attributes',
       'set_attribute_field_name' => 'set_attribute_options',
       'product'=> $product->exists && $product->type == "simple"?$product->sku->first():$product,
   ])

    {!! \Html::script('assets/corals/plugins/nestable/select2totree.js') !!}
    <script type="application/javascript">
        $(document).ready(function () {
            $('input[name="external"]').on('change', function () {
                if ($(this).prop('checked')) {
                    $('#external_link').fadeIn();
                } else {
                    $('#external_link').fadeOut();
                }
            });

            $('input[name="shipping[shipping_option]"]').on('change', function () {
                let ele = $(this);

                $('.shipping-options').hide();

                if (ele.is(':checked')) {
                    $(`#${ele.val()}`).fadeIn();

                    if (ele.val() === 'flat_rate_prices' && $('.location-rate').length === 0) {
                        $("#flat-rate-add-location").click();
                    }
                }
            });

            $('input[name="price_per_classification"]').on('change', function () {
                if ($(this).prop('checked')) {
                    $('#classification_price_section').fadeIn();
                } else {
                    $('#classification_price_section').fadeOut();
                }
            });


            $('input[name="shipping[enabled]"]').on('change', function () {

                if ($(this).val() == 1) {
                    $('#shipping').fadeIn();
                } else {
                    $('#shipping').hide();
                }
            });

            $('select[name="type"]').on('change', function () {
                $product_type = $(this).val();
                if ($product_type === "simple") {
                    $('.simple_product_attributes').removeClass('hidden');
                    $('.variable_product_attributes').addClass('hidden');
                    setInventoryValue('{{ old('inventory', $sku->inventory) }}');
                } else if ($product_type === "variable") {
                    $('.simple_product_attributes').addClass('hidden');
                    $('.variable_product_attributes').removeClass('hidden');
                } else {
                    $('.simple_product_attributes').addClass('hidden');
                    $('.variable_product_attributes').addClass('hidden');
                }
            });

            $('select[name="type"]').trigger('change');
            $('#inventory').change(function (event) {
                var value = $(this).val();
                setInventoryValue(value);
            });
        });

        function togglePremuimContent() {
            var input = $('#private_content_pages');
            if (input.prop('checked')) {
                $('#product_pages').fadeIn();
            } else {
                $('#product_pages').fadeOut();
            }
        }

        function toggleExternalURL() {
            var input = $('#external');
            if (input.prop('checked')) {
                $('#external_section').fadeIn();
            } else {
                $('#external_section').fadeOut();
            }
        }

        function setInventoryValue(value) {
            var input = '';

            if (value === 'bucket') {
                input = '{{ CoralsForm::select('inventory_value','Inventory Value', get_array_key_translation(config('marketplace.models.sku.bucket')),false,$sku->inventory_value?$sku->inventory_value:null )  }}';
            } else if (value === 'finite') {
                input = '{{ CoralsForm::number('inventory_value','Inventory Value',false,$sku->inventory_value?$sku->inventory_value:null,
                                    ['help_text'=>'',
                                    'step'=>1,'min'=>0,'max'=>999999])  }}';
            } else {
                input = '';
            }

            $("#inventory_value_wrapper").html(input);

            if (input !== '') {
                $("#inventory_value_wrapper").show();
            } else {
                $("#inventory_value_wrapper").hide();
            }
        }

        window.addEventListener('attributeSetsChanged', (event) => {
            let variationOptions = $("#variation_options");

            let currentSelection = variationOptions.val();

            if (variationLoadValues) {
                currentSelection = variationLoadValues;
                variationLoadValues = undefined;
            }

            let select2Data = [];

            let newSelection = [];

            for (let option in event.detail.attributes_list) {
                if (currentSelection.includes(option) || currentSelection.includes(parseInt(option))) {
                    newSelection.push(option);
                }

                select2Data.push({id: option, text: event.detail.attributes_list[option]});
            }

            variationOptions.empty();

            variationOptions.select2({data: select2Data});

            variationOptions.val(newSelection);

            variationOptions.trigger('change');
        }, false);

        $(document).on('change', '#variation_options', function (event) {
            let currentSelection = $(this).val();

            $(this).find("option").each(function () {
                let option = $(this).val();
                let optionField = $(`[name="set_attribute_options[${option}]"]`);
                if (currentSelection.includes(option)) {
                    if (optionField.length) {
                        optionField.closest('.form-group').addClass('hidden');
                    }
                } else if (optionField.length) {
                    optionField.closest('.form-group').removeClass('hidden');
                }
            });
        })

        $('#categories').on('change', function () {
            let select = $(this);

            let currentVal = select.val();

            let newVal = [];

            currentVal.forEach((v, index) => {
                newVal.push(v);

                let selectedOption = select.find(`option[value="${v}"]`);

                while (selectedOption.length && selectedOption.data('pup')) {
                    newVal.push(selectedOption.data('pup'));
                    v = selectedOption.data('pup');
                    selectedOption = select.find(`option[value="${v}"]`);
                }
            });

            select.val(newVal);
        })
        $(`[name='bulk_price']`).on('change', handleBulkPriceChange = function () {

            switch ($(`[name='bulk_price']:checked`).val()) {
                case 'none':
                    $('#price-per-quantity-section').hide();
                    $('#offer-section').hide();
                    break;
                case 'offers':
                    $('#price-per-quantity-section').hide();
                    $('#offer-section').show();
                    break;
                case 'price_per_quantity':
                    $('#price-per-quantity-section').show();
                    $('#offer-section').hide();
                    break;
                default:
                case 'none':
                    $('#price-per-quantity-section').hide();
                    $('#offer-section').hide();
            }
        });

        handleBulkPriceChange();

    </script>
@endsection
