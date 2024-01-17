{!! CoralsForm::openForm($order,['class'=>'ajax-form','id'=>'update-order-form','data-page_action'=>"closeModal","data-table"=>'.dataTableBuilder']) !!}
<div class="row">
    <div class="col-md-6">
        {!! CoralsForm::select('status','Marketplace::attributes.order.status_order', $order_statuses ,true) !!}
        {!! CoralsForm::checkbox('notify_buyer', 'Marketplace::attributes.order.notify_buyer') !!}
    </div>
    <div class="col-md-6">
        {!! CoralsForm::select('shipping[status]','Marketplace::attributes.order.shipping_status', $order_statuses ,false, $order->shipping['status'] ?? '',['class'=>'']) !!}
        {!! CoralsForm::text('shipping[tracking_number]','Marketplace::attributes.order.shipping_track', false, $order->shipping['tracking_number'] ?? '',['class'=>'']) !!}
        {!! CoralsForm::text('shipping[label_url]','Marketplace::attributes.order.shipping_label', false, $order->shipping['label_url'] ?? '',['class'=>'']) !!}
    </div>
</div>
@if (user()->hasPermissionTo('Marketplace::order.update_payment_details'))
    <div class="row">
        <div class="col-md-12">
            {!! CoralsForm::select('billing[payment_status]','Marketplace::attributes.order.payment_status', $payment_statuses , false, $order->billing['payment_status'] ?? '',['class'=>'']) !!}
            {!! CoralsForm::text('billing[gateway]','Marketplace::attributes.order.payment_method', false, $order->billing['gateway'] ?? '',['class'=>'']) !!}
            {!! CoralsForm::text('billing[payment_reference]','Marketplace::attributes.order.payment_reference', false, $order->billing['payment_reference'] ?? '',['class'=>'']) !!}
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-12">
        {!! CoralsForm::formButtons(trans('Corals::labels.save',['title' => $title_singular]), [], ['show_cancel' => false])  !!}
    </div>
</div>


{!! CoralsForm::closeForm($order) !!}

