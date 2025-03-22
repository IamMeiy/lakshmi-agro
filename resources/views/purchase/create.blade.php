@extends('layouts.master')

@section('title', 'New Purchase')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6>New Purchase</h6>
            <a href="{{ route('purchase.index') }}" class="btn btn-primary btn-sm">Back</a>
        </div>
    </div>
    <div class="card-body">
        <form id="purchaseForm">
            @csrf

            <!-- Small Date Input -->
            <div class="form-group d-flex align-items-center">
                <label for="purchase_date" class="mr-2">Date:</label>
                <input type="date" name="purchase_date" id="purchase_date" class="form-control form-control-sm" style="width: 150px;" value="{{ date('Y-m-d') }}" required>
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
                    <tr>
                        <td>
                            <select name="product_variant_id[]" class="form-control product-select select2" style="width: 300px;">
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
                </tbody>
            </table>

            <button type="button" id="addRow" class="btn btn-success btn-sm">+ Add Item</button>
            <button type="submit" id="submitBtn" class="btn btn-primary btn-sm">Submit Purchase</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // Add new row
        $('#addRow').click(function(){
            let newRow = `
                <tr>
                    <td>
                        <select name="product_variant_id[]" class="form-control product-select select2"  style="width: 300px;">
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
            select2();
        });

        // Remove row
        $(document).on('click', '.removeRow', function(){
            $(this).closest('tr').remove();
        });

        /* Below code for to store the data */
        $(document).on('submit', '#purchaseForm', function(event){
            event.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: '{{ route('purchase.store') }}',
                type: 'POST',
                data: formData,
                beforeSend: function(){
                    $('#submitBtn').attr('disabled', true);
                },
                success: function(res){
                    $('#submitBtn').attr('disabled', false);
                    if(res.status == 'success'){
                        successMessage(res.message);
                        $('#purchaseForm').trigger('reset');
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    }
                    else{
                        showError(res.message);
                    }
                },
                error: function(err){
                    $('#submitBtn').attr('disabled', false);
                    errorMessage(err);
                }
            });
        });
        });
</script>
@endsection
