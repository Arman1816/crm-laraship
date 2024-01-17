@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('invoice_show', $invoice) }}
        @endslot
    @endcomponent
@endsection
@section('actions')
    {!! CoralsForm::link(url($resource_url . '/' . $invoice->hashed_id . '/download'), trans('Corals::labels.download'),
     ['class'=>'btn btn-primary']) !!}

    @if($invoice->status != 'paid' && \Modules::isModuleActive('corals-ecommerce'))
        @if(get_class($invoice->invoicable) == \Corals\Modules\Ecommerce\Models\Order::class && user()->can('payOrder', $invoice->invoicable))
            {!! CoralsForm::link(url('e-commerce/checkout/?order=' . $invoice->invoicable->hashed_id), trans('Payment::labels.invoice.pay'),
             ['class'=>'btn btn-success']) !!}
        @endif
    @elseif($invoice->status == 'pending' && !$gatewayPaymentDetails)
        {!! CoralsForm::link($invoice->present('public_link'), trans('Payment::labels.invoice.pay'),
         ['class'=>'btn btn-success','target' => '_blank']) !!}
    @endif
@endsection
@section('content')
    @component('components.box',['box_class'=>'box-success'])
        @include('Payment::invoices.invoice', ['invoice' => $invoice, 'PDF'=>false])
        <hr/>
        @if($invoice->status == 'pending' && $gatewayPaymentDetails)
            <div>
                {!! $gatewayPaymentDetails !!}
            </div>
        @endif
    @endcomponent
@endsection
