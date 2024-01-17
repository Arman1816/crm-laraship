<div id="offer-section" style="display: none">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive" id="offers-section-table">
                <table class="table">
                    <caption>
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="p-0 m-0">
                                    @lang('Marketplace::attributes.product.offers.title')
                                </h3>
                            </div>
                            <div class="col-md-6 text-right">
{{--                                <a href="#" id="add-offer-row" class="btn btn-success btn-sm">--}}
{{--                                    @lang('Marketplace::attributes.product.offers.add_offer')--}}
{{--                                </a>--}}
                            </div>
                        </div>
                    </caption>
                    <thead>
                    <tr>
                        <th class="text-center">
                            @lang('Marketplace::attributes.product.offers.type')
                        </th>

                        <th class="text-center">@lang('Marketplace::attributes.product.offers.quantity')</th>

                        <th class="text-center">@lang('Marketplace::attributes.product.offers.value')</th>
                        <th>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse(data_get($product,'offers',[])  as $index => $offer)
                        @include('Marketplace::products.partials.offer_row',['index'=>$index,'offer'=>$offer,'deletable'=> !!$index])
                    @empty
                        @include('Marketplace::products.partials.offer_row',['index'=>0,'offer'=>[]])
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('partial_js')
    <script>
        {{--$(document).on('click', '#add-offer-row', function (e) {--}}
        {{--    e.preventDefault();--}}

        {{--    let index = $('#offers-section-table tbody tr').length;--}}

        {{--    $.get(`{{url('marketplace/products/render-offer-section-row')}}?index=${index}`, (viewResult) => {--}}
        {{--        $('#offers-section-table tbody').append(viewResult);--}}
        {{--    })--}}

        {{--    $(`#price-offers-checkbox-wrapper`).hide();--}}
        {{--})--}}

        // $(document).on('click', '.remove-offer-row', function (e) {
        //     e.preventDefault();
        //     $(this).closest('tr').remove();
        //     let offerRowsLength = $('#offers-section-table tbody tr').length;
        //
        //
        //     if (offerRowsLength == 0) {
        //         $(`#price-offers-checkbox-wrapper`).show();
        //
        //     }
        // })


        $(document).on('change', '.offer-type', function () {
            let targetIndex = $(this).data('related_index'),
                helpText;

            if (!targetIndex) {
                targetIndex = 0;
            }

            switch ($(this).val()) {
                case 'bundle_price':
                    helpText = `@lang('Marketplace::attributes.product.offers.price')`;
                    break;
                case 'get_free':
                    helpText = `@lang('Marketplace::attributes.product.offers.free_items')`;
                    break;
            }


            $(`[name='offers[${targetIndex}][value]']`).next().html(helpText);

        });


    </script>
@endpush
