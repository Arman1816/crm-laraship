<div id="price-per-quantity-section" style=" display: none ">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive" id="price-per-quantity-section-table">
                <table class="table">
                    <caption>
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="p-0 m-0">
                                    @lang('Marketplace::attributes.product.price_per_quantity.title')
                                </h3>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="#" id="add-price-per-quantity-row" class="btn btn-success btn-sm">
                                    @lang('Marketplace::attributes.product.price_per_quantity.add_price_per_quantity')
                                </a>
                            </div>
                        </div>
                    </caption>
                    <thead>
                    <tr>
                        <th class="text-center" colspan="2">
                            @lang('Marketplace::attributes.product.price_per_quantity.quantity')
                        </th>
                        <th class="text-center">@lang('Marketplace::attributes.product.price_per_quantity.price')</th>
                        <th class="text-center">@lang('Marketplace::attributes.product.price_per_quantity.type')</th>
                        <th class="text-center">@lang('Marketplace::attributes.product.price_per_quantity.description')</th>
                        <th>
                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse(data_get($product,'price_per_quantity',[])  as $index => $pricePerQuantity)
                        @include('Marketplace::products.partials.price_per_quantity_row',['index'=>$index,'pricePerQuantity'=>$pricePerQuantity,  'deletable'=>!!$index ])
                    @empty
                        @include('Marketplace::products.partials.price_per_quantity_row',['index'=>0,'pricePerQuantity'=>[]])
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('partial_js')
    <script>
        $(document).on('click', '#add-price-per-quantity-row', function (e) {
            e.preventDefault();

            let index = $('#price-per-quantity-section-table tbody tr').length;

            $.get(`{{url('marketplace/products/render-price-per-quantity-section-row')}}?index=${index}`, (viewResult) => {
                $('#price-per-quantity-section-table tbody').append(viewResult);
            })

            $(`#price-per-quantity-checkbox-wrapper`).hide();
        })

        $(document).on('click', '.remove-price-per-quantity-row', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            let pricePerQuantityRowsLength = $('#price-per-quantity-section-table tbody tr').length;

            if (pricePerQuantityRowsLength == 0) {
                $(`#price-per-quantity-checkbox-wrapper`).show();
            }
        })

    </script>
@endpush
