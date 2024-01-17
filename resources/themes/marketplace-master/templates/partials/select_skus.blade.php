<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <select class="select-product-skus" style="width: 100%" name="sku_hash">

                <option selected disabled data-default-option="1">
                    @lang('corals-marketplace-master::labels.template.product_single.select_sku')
                </option>

                @foreach($product->activeSKU as $sku)
                    <option value="{{$sku->hashed_id}}" data-image="{{asset($sku->image)}}"
                            data-html_price='<b>{!! $sku->discount? sprintf("<del class=\"text-muted\">%s</del>",\Payments::currency($sku->regular_price)) :''  !!} {!! \Payments::currency($sku->price) !!} </b>'
                            @if($sku->stock_status!='in_stock') disabled @endif>
                        {!! !$sku->options->isEmpty() ? $sku->presenter()['options']:'' !!}

                        @if($sku->stock_status!='in_stock')
                            -
                            <b>@lang('corals-marketplace-master::labels.partial.out_stock')</b>
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</div>



@push('partial_js')
    <script>
        function formatSKUs(state) {

            let defaultOption = $(state.element).data('default-option');

            if (!state.id || defaultOption) {
                return state.text;
            }


            let img = $(state.element).data('image'),
                htmlPrice = $(state.element).data('html_price');

            return $(`<span  ><img src="${img}" class="img-fluid" style="width: 10%" />  ${state.text}  ${htmlPrice} </span>`);
        }

        $(".select-product-skus").select2({
            templateResult: formatSKUs
        });
    </script>
@endpush