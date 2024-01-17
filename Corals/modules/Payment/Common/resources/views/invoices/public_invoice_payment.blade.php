@extends('layouts.blank')

@section('title',$title)

@section('content')
    @include('Payment::invoices.invoice', ['invoice' => $invoice, 'PDF'=>false])

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($invoice->status=='paid')
                    <br>
                    <a class="btn btn-success" style="margin-top: 30px"
                       href="{{url("invoice/payments/$invoice->hashed_id/download")}}"
                       target="_blank">
                        @lang('Payment::labels.invoice.download_invoice')
                    </a>
                @else
                    <form action="{{url("invoice/payments/$invoice->hashed_id/do-pay")}}"
                          method="post"
                          id="payment-form"
                          data-page_action="redirectTo">
                        <div>
                            @include('Payment::invoices.partials.payment')


                            <div class="row">
                                <button id="checkout-pay" type="submit" class="btn btn-primary submit-btn">
                                    @lang('Payment::labels.pay')
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

