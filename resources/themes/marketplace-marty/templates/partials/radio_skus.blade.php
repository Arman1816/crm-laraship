@foreach($product->activeSKU->chunk('4') as $chunkedSkus)
    <div class="d-block">
        @foreach($chunkedSkus as $sku)
            <div class="text-center sku-item mr-2 d-block">

                @if($sku->stock_status == "in_stock")
                    <button type="button"
                            class="btn btn-block btn-sm btn-default btn-secondary btn-radio mb-1">
                        {!! !$sku->options->isEmpty() ? $sku->presenter()['sku_options']:'' !!}
                        <b class="pull-right">{!! $sku->discount?'<del class="text-muted">'.\Payments::currency($sku->regular_price).'</del>':''  !!} {!! \Payments::currency($sku->price) !!}</b>
                    </button>
                @else
                    <button type="button"
                            class="btn btn-block btn-sm mb-1 btn-danger ">
                        <b> @lang('corals-marketplace-marty::labels.partial.out_stock')</b>
                    </button>
                @endif
                <input type="checkbox" id="left-item" name="sku_hash"
                       value="{{ $sku->hashed_id }}"
                       class="hidden d-none disable-icheck"/>
            </div>
        @endforeach
    </div>
@endforeach

<div class="form-group">
    <span data-name="sku_hash"></span>
</div>