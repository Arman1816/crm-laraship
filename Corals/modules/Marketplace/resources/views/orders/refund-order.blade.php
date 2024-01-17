<div class="row">
    <div class="col-md-12">
        @component('components.box')
            {!! CoralsForm::openForm($order,['url'=>url($resource_url.'/'.$order->hashed_id.'/do-refund'), 'method'=>'PUT','class'=>'ajax-form','data-page_action'=>"closeModal","data-table"=>'.dataTableBuilder']) !!}
            <div class="row">
                <div class="col-md-12">
                    {!! CoralsForm::text('amount','Marketplace::attributes.order.amount', true, $order->amount - $order->getPaymentRefundedAmount(),['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! CoralsForm::radio('type','Marketplace::attributes.order.refund_method',true, trans('Marketplace::attributes.order.status_refund'),null,['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! CoralsForm::checkbox('cancel', 'Marketplace::attributes.order.cancel', 1) !!}
                </div>
            </div>
            @if($order->payouts)
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::checkbox('reverse_payout', 'Marketplace::attributes.order.reverse_payout', 1) !!}
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    {!! CoralsForm::formButtons(trans('Corals::labels.save',['title' => 'Refund']), [], ['show_cancel' => false])  !!}
                </div>
            </div>
            {!! CoralsForm::closeForm($order) !!}
        @endcomponent
    </div>
</div>
