@extends('layouts.master')

@section('title', 'View Purchase')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Purchase Details</h6>
        <a href="{{ route('purchase.index') }}" class="btn btn-primary btn-sm">Back</a>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Purchase Date:</strong> {{ $purchase->purchase_date }} <br>
            <strong>Total Amount:</strong> ₹{{ number_format($purchase->total_amount, 2) }}
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Previous Stock</th>
                    <th>Purchased Stock</th>
                    <th>Balance Stock</th>
                    <th>Purchase Price</th>
                    <th>Final Price</th> <!-- Added Final Price Column -->
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $item)
                @php
                    $final_price = $item->purchased_stock * $item->purchase_price; // Calculate final price
                @endphp
                <tr>
                    <td>{{ $item->productVariant->product->name }} - {{ $item->productVariant->quantity }}g</td> <!-- Updated Name Format -->
                    <td>{{ $item->previous_stock }}</td>
                    <td>{{ $item->purchased_stock }}</td>
                    <td>{{ $item->balance_stock }}</td>
                    <td>₹{{ number_format($item->purchase_price, 2) }}</td>
                    <td>₹{{ number_format($final_price, 2) }}</td> <!-- Display Final Price -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
