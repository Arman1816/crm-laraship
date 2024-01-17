@if($PDF)
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->code }}</title>
    @endif
    <style type="text/css">
        #page-wrap {
            width: 700px;
            margin: 0 auto;
        }

        table.outline-table {
            border: 1px solid #D3D3D3;
            border-spacing: 0;
        }

        tr.border-bottom td, td.border-bottom {
            border-bottom: 1px solid #D3D3D3;
        }

        tr.border-top td, td.border-top {
            border-top: 1px solid #D3D3D3;
        }

        tr.border-right td, td.border-right {
            border-right: 1px solid #D3D3D3;
        }

        tr.border-right td:last-child {
            border-right: 0px;
        }

        tr.center td, td.center {
            text-align: center;
            vertical-align: text-top;
        }

        td.pad-left {
            padding-left: 5px;
        }

        tr.right-center td, td.right-center {
            text-align: right;
            padding-right: 50px;
        }

        tr.right td, td.right {
            text-align: right;
        }

        .grey {
            background: grey;
        }

        .invoice-items-table td {
            padding: 5px;
        }

        .invoice-items-table thead tr {
            background-color: #D3D3D3;
        }

        .invoice-items-table thead th {
            padding: 5px;
        }

        .status-label-td .label-info {
            border-color: #00c0ef !important;
            color: #00c0ef !important;
        }

        .status-label-td .label-success {
            border-color: #00a65a !important;
            color: #00a65a !important;
        }

        .status-label-td .label-danger {
            border-color: #dd4b39 !important;
            color: #dd4b39 !important;
        }

        .status-label-td .label-primary {
            border-color: #3c8dbc !important;
            color: #3c8dbc !important;
        }

        .status-label-td.label-warning {
            border-color: #f39c12 !important;
            color: #f39c12 !important;
        }

        td.status-label-td .label {
            background: unset !important;
            display: inline-block;
            padding: 15px;
            margin-top: 15px;
            font-size: 15px;
            width: 100px;
            font-weight: 700;
            line-height: 1;
            border-radius: 10px;
            border: 2px solid;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }

        td.status-label-td {
            text-align: center;
        }

    </style>
    @if($PDF)
</head>
<body>
@endif
<div id="page-wrap">
    <table style="width:100%">
        <tbody>
        <tr>
            <td style="width:65%; vertical-align: top;padding-top: 20px">
                <img style="max-width: 250px;" src="{{ \Settings::get('site_logo') }}">
            </td>
            <td style="width: 35%; vertical-align: top;">
                <h2>@lang('Payment::labels.invoice.title')</h2>
                {{$invoice->invoicable ? $invoice->invoicable->getInvoiceReference('pdf') : '-' }}<br/><br/>
                <strong>@lang('Payment::labels.invoice.date'):</strong> {{ format_date($invoice->invoice_date) }}<br>
                <strong>@lang('Payment::labels.invoice.number'):</strong> {{ $invoice->code }}<br>
                @if($invoice->reference_id)
                    <strong>@lang('Payment::labels.invoice.reference_id'):</strong> {{ $invoice->reference_id }}<br>
                @endif
                <strong>@lang('Payment::attributes.invoice.due_date'):</strong> {{ format_date($invoice->due_date) }}
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width:50%;vertical-align: top;">
                <h3>@lang('Payment::labels.invoice.payable_to')</h3>
                <hr/>
                {!! $invoice->invoicable && method_exists($invoice->invoicable, 'getInvoicePayableTo') ? $invoice->invoicable->getInvoicePayableTo() : '-' !!}
            </td>
            <td style="width:50%;vertical-align: top;">
                <h3>@lang('Payment::labels.invoice.bill_to')</h3>
                <hr/>
                {{ $invoice->present('customer') }}<br/>
                {{ $invoice->present('email') }}<br/>
                @if ($invoice->present('phone'))
                    {{ $invoice->present('phone') }}<br>
                @endif
                {!! $invoice->present('billing_address') !!}
            </td>
        </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <table style="width:100%;" class="outline-table invoice-items-table">
        <thead>
        <tr class="border-bottom border-right">
            <th>@lang('Payment::labels.invoice.description')</th>
            <th style="width: 50px;">@lang('Payment::labels.invoice.quantity')</th>
            <th style="width: 100px;" class="center">@lang('Payment::labels.invoice.amount')</th>
        </tr>
        </thead>
        <tbody>
        <!-- Display The Invoice Items -->
        @foreach ($invoice->items as $item)
            <tr class="border-bottom border-right">
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="center" style="padding-right: 5px;">
                    {{ \Payments::currency_convert($item->amount, null, $invoice->currency) }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <table style="width:100%;">
        <tr>
            <td style="width: 50%;" class="status-label-td">
                {!! $invoice->present('status') !!}
            </td>
            <td style="width: 50%;">
                <table style="width:100%;" class="invoice-items-table">
                    <tbody>
                    <tr class="border-bottom">
                        <td class="right">@lang('Payment::labels.invoice.sub_total')</td>
                        <td style="width: 100px;" class="center">{{ $invoice->present('sub_total') }}</td>
                    </tr>
                    <tr class="border-bottom">
                        <td class="right">@lang('Payment::labels.invoice.total')</td>
                        <td class="center">{{ $invoice->present('total') }}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    @if(!empty($invoice->terms))
        <table>
            <tbody>
            <tr>
                <td>
                    <h3>@lang('Payment::attributes.invoice.terms')</h3>
                    {!! $invoice->terms !!}
                </td>
            </tr>
            </tbody>
        </table>
    @endif
</div>
@if($PDF)
</body>
</html>
@endif
