@extends('layouts.master')

@section('title', $invoice->invoice_number)

@section('content')
{{-- Below card will show the invoice details --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Invoice Details</h6>
                <div class="btn-group">
                    <!-- Preview PDF -->
                    <a href="{{ route('invoice.preview', $invoice->id) }}" target="_blank" class="btn btn-success btn-sm">Preview</a>
                    <!-- Download PDF -->
                    <a href="{{ route('invoice.download', $invoice->id) }}" class="btn btn-primary btn-sm ml-2">Download</a>
                    <a href="{{ route('invoice.index') }}" class="btn btn-primary btn-sm ml-2">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <th>CUSTOMER</th>
                    <th>INVOICE</th>
                    <th>DATE</th>
                    <th>PAYMENT MODE</th>
                    <th>TOTAL AMOUNT</th>
                    <th>PAID AMOUNT</th>
                    @if ($invoice->balance_amount != 0)
                        <th>PENDING AMOUNT</th>
                    @endif
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->created_at->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $invoice->payment_mode }}</td>
                        <td>{{ $invoice->final_price }}</td>
                        <td>{{ $invoice->amount_paid }}</td>
                        @if ($invoice->balance_amount != 0)
                            <td>{{ $invoice->balance_amount }}</td>
                        @endif
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                    <th>PRODUCT</th>
                    <th>PRICE</th>
                    <th>QUANTITY</th>
                    <th>TOTAL</th>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td>{{ $item->variant->product->name }} - {{ $item->variant->quantity }}g</td>
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">SUB TOTAL</td>
                        <td>{{ $invoice->final_price }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    
@endsection