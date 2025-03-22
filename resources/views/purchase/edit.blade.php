@extends('layouts.master')

@section('title', 'Edit Purchase')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Edit Purchase</h6>
        <a href="{{ route('purchase.index') }}" class="btn btn-primary btn-sm">Back</a>
    </div>
    <div class="card-body">
        <div id="alertBox"></div> <!-- Alert Message Box -->
        <form id="editPurchaseForm">
            @csrf

            <input type="hidden" id="purchase_id" name="purchase_id" value="{{ $purchase->id }}">

            <div class="form-group d-flex align-items-center">
                <label for="purchase_date" class="mr-2">Date:</label>
                <input type="date" name="purchase_date" id="purchase_date" 
                class="form-control form-control-sm" style="width: 150px;" 
                value="{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') }}" required>
         
            </div>

            <table class="table table-bordered" id="purchaseTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Purchase Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->items as $index => $item)
                    <tr>
                        <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                        <td>
                            <select name="product_variant_id[]" class="form-control custom-select2" style="width: 200px;" required>
                                @foreach($products as $product)
                                    @foreach($product->variants as $variant)
                                        <option value="{{ $variant->id }}" {{ $variant->id == $item->productVariant->id ? 'selected' : '' }}>
                                            {{ $product->name }} - {{ $variant->quantity }}g
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="quantity[]" class="form-control quantity" min="1" value="{{ $item->purchased_stock }}" required></td>
                        <td><input type="number" step="0.01" name="purchase_price[]" class="form-control price" value="{{ $item->purchase_price }}" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" id="addRow" class="btn btn-success btn-sm">+ Add Item</button>
            <button type="submit" id="updateBtn" class="btn btn-primary btn-sm">Update Purchase</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        applyCustomSelect();

        $('#addRow').click(function(){
            let newRow = `
                <tr>
                    <td>
                        <select name="product_variant_id[]" class="form-control custom-select2" style="width: 200px;" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $product->name }} - {{ $variant->quantity }}g</option>
                                @endforeach
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" min="1" required></td>
                    <td><input type="number" step="0.01" name="purchase_price[]" class="form-control price" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                </tr>
            `;
            $('#purchaseTable tbody').append(newRow);
            applyCustomSelect();
        });

        $(document).on('click', '.removeRow', function(){
            $(this).closest('tr').remove();
        });

        // ✅ AJAX form submission for updating purchase
        $(document).on('submit', '#editPurchaseForm', function(event){
            event.preventDefault();
            let formData = $(this).serializeArray();
            $.ajax({
                url: '{{ route('purchase.update') }}',
                type: 'POST',
                data: formData,
                beforeSend: function(){
                    $('#updateBtn').attr('disabled', true);
                },
                success: function(res){
                    $('#updateBtn').attr('disabled', false);
                    if(res.status == 'success'){
                        successMessage(res.message);
                        $('#editPurchaseForm').trigger('reset');
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }
                    else{
                        showError(res.message);
                    }
                },
                error: function(err){
                    $('#updateBtn').attr('disabled', false);
                    errorMessage(err);
                }
            });
        });
        });

    function applyCustomSelect() {
        $('.custom-select2').select2({ width: '200px' });
    }
</script>
@endsection
