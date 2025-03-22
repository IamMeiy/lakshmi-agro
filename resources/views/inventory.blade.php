@extends('layouts.master')

@section('title', 'Inventory')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6>Stocks</h6>
            <div class="d-flex" style="gap: 10px;">  <!-- Added gap -->
                <select id="productFilter" class="form-control select2" style="width: 200px;">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->name }}">{{ $product->name }}</option>
                    @endforeach
                </select>
        
                <select id="stockFilter" class="form-control select2" style="width: 200px;">
                    <option value="">All Stock</option>
                    <option value="no_stock">No Stock</option>
                    <option value="low_stock">Low Stock</option>
                </select>
            </div>
        </div>        
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="stockTable">
            <thead>
                <th>ID</th>
                <th>PRODUCT NAME</th>
                <th>QUANTITY</th>
                <th>STOCK</th>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        let inventoryTable = $('#stockTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('inventory.table') }}",
                data: function(d) {
                    d.product_name = $('#productFilter').val(); // Product filter
                    d.stock_filter = $('#stockFilter').val(); // Stock filter
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { 
                    data: 'id', 
                    name: 'id',
                    render: function(data, type, row){
                        return `${row.product.name} - ${row.quantity}g`;
                    }
                },
                { data: 'quantity', name: 'quantity' },
                { data: 'stock_quantity', name: 'stock_quantity' }
            ]
        });

        // Reload table when filters change
        $('#productFilter, #stockFilter').change(function() {
            inventoryTable.ajax.reload();
        });
    });
</script>
@endsection
