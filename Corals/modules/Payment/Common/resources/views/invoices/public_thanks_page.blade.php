@extends('layouts.blank')

@section('title',$title)

@section('content')
    <div class="container">
        <div class="row" style="margin-top: 20px">
            <div class="col-md-4">
                <img style="max-width: 250px;" alt="logo" src="{{ \Settings::get('site_logo') }}">
            </div>
            <div class="col-md-4 text-center" style="margin-top: -20px">
                <h2>@lang('Payment::labels.invoice.title')</h2>
                {{$invoice->invoicable ? $invoice->invoicable->getInvoiceReference('pdf') : '-' }}<br/><br/>
            </div>
            <div class="col-md-4">
                <strong>@lang('Payment::labels.invoice.date'):</strong> {{ format_date($invoice->invoice_date) }}
                <br>
                <strong>@lang('Payment::labels.invoice.number'):</strong> {{ $invoice->code }}<br>
                <strong>@lang('Payment::attributes.invoice.due_date')
                    :</strong> {{ format_date($invoice->due_date) }}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center" style="margin: 15% 0">
                    <h1 class="display-3">Thank You!</h1>
                    <i class="fa fa-check text-success" style="font-size: 50px"></i>
                    <p class="lead">
                        @if($gatewayPaymentDetails)
                            {!! $gatewayPaymentDetails !!}
                        @else
                            Your<strong> {{$invoice->code}}</strong> has been Successfully paid
                            <br>
                            with full amount {{\Payments::currency( $invoice->total)}}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
