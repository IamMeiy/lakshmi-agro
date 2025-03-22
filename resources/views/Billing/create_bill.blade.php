@extends('layouts.master')

@section('title', 'Create Inovice')

@section('content')
{{-- Below code for the card content --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Create Invoice</h6>
                <a href="{{ route('invoice.index') }}" class="btn btn-primary btn-sm">Back</a>
            </div>
        </div>
        <form action="" id="cartFrm">
            @csrf
            <div class="card-body">
                <div class="row d-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <select name="customer" id="customer" class="form-control select2" data-placeholder="Select Customer">
                            <option value="">Select Customer</option>
                            @forelse ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->mobile }} - {{ $customer->name }}</option>
                            @empty
                                <div class="text-center">
                                    No Customer available
                                </div>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="product" class="form-control select2" id="variant" data-placeholder="Select Product">
                            <option value="">Select Product</option>
                            @forelse ($variants as $variant)
                                @if ($variant->stock_quantity != 0)
                                    <option value="{{ $variant->id }}">{{ $variant->product->name }} - {{ $variant->quantity }}g</option>
                                @endif
                            @empty
                                <div class="text-center">
                                    No Product available
                                </div>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" id="addProduct">Add Product</button>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <th class="text-uppercase">Product</th>
                        <th class="text-uppercase">MRP</th>
                        <th class="text-uppercase">Price</th>
                        <th class="text-uppercase">Quantity</th>
                        <th class="text-uppercase">Final Price</th>
                        <th class="text-uppercase">Action</th>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                    <tfoot id="table-foot">
                        
                    </tfoot>
                </table>
                <div class="row mt-3" id="balance">

                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" id="previewBtn" type="button">Get Price</button>
                <div class="d-flex align-items-center">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" name="isPaid" id="isPaid" checked>
                        <label class="form-check-label" for="isPaid">Amount Paid</label>
                    </div>
                    <button class="btn btn-success ml-2" id="submitBtn" type="submit" disabled>Generate</button>
                </div>
            </div>
            
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            /* below function to add product to cart */
            $(document).on('click','#addProduct', function(event){
                event.preventDefault();
                let variant = $('#variant').val();
                $.ajax({
                    url: '{{ route('get.variant') }}',
                    type: 'GET',
                    data: {
                        variant_id : variant
                    },
                    beforeSend: function(){
                        $('#addProduct').prop('disabled', true);
                        $('#variant').val('').change();
                    },
                    success: function(result){
                        if(result.status == 'error'){
                            showError(result.message);
                        }
                        else{
                            $('#table-body').append(
                                `
                                <tr>
                                    <td>${result.product.name} - ${result.quantity}g</td>
                                    <td>${result.mrp}</td>
                                    <td class="base-price">${result.price}</td>
                                    <td>
                                        <input type="hidden" name="products[]" value="${result.id}">
                                        <input type="number" name="quantity[]" class="form-control quantity" value="1" min="1">
                                    </td>
                                    <td class="final-price">${result.price}</td>
                                    <td><button class="btn btn-danger remove-btn" type="button">Remove</button></td>
                                </tr>
                                `
                            );
                        }
                        $('#addProduct').prop('disabled', false);
                    }
                });
                
            });

            /* below function to remove the product from cart */
            $(document).on('click', '.remove-btn', function(event){
                $(this).closest('tr').remove();
            });

            /* below function to get the final price */
            $(document).on('click', '#previewBtn', function(event){
                event.preventDefault();
                let data = $('#cartFrm').serializeArray();
                $.ajax({
                    url: '{{ route('invoice.finalPrice') }}',
                    type: 'GET',
                    data: data,
                    beforeSend: function(){
                        $('#previewBtn').prop('disabled', true);
                        $('#previewBtn').text('Please Wait...');
                        $('#table-foot').empty();
                        $('#balance').empty();
                        $('#submitBtn').prop('disabled', true);
                    },
                    success: function(result){
                        if(result.status == 'success'){
                            $('#table-foot').append(
                                `
                                <tr>
                                    <td colspan="3">Total</td>
                                    <td>${result.quantity}</td>
                                    <td colspan="2">${result.price}</td>
                                </tr>
                                `
                            );
                            $('#balance').append(
                                `
                                <div class="col-md-4">
                                    <label class="form-label">Final Amount</label>
                                    <input type="number" class="form-control" name="final_price" value="${result.price}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Amount Paid</label>
                                    <input type="number" class="form-control" name="amount_paid" value="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Payment Type</label>
                                    <select name="payment_type" class="form-control select2" data-placeholder="Select Payment">
                                        <option value="">Select Payment</option>
                                        @forelse (PAYMENT_TYPE as $index => $payment)
                                            <option value="{{ $payment }}">{{ $payment }}</option>
                                        @empty
                                            <div class="text-center">
                                                No Payment Mode available
                                            </div>
                                        @endforelse
                                    </select>
                                </div>
                                `
                            );

                            $('#submitBtn').prop('disabled', false);
                        }
                        else{
                            showError(result.message);
                        }
                        $('#previewBtn').prop('disabled', false);
                        $('#previewBtn').text('Get Price');
                    },
                    error: function(err){
                        $('#previewBtn').prop('disabled', false);
                        $('#previewBtn').text('Get Price');
                        $('#submitBtn').prop('disabled', true);
                        errorMessage(err);
                    }
                })
            });

            /* below function for the price on change the quantity */
            $(document).on('change', '.quantity', function(event){
                event.preventDefault();
                let base_price = $(this).closest('tr').find('.base-price').text();
                let quantity = $(this).val();
                let final_price = parseFloat(base_price) * parseInt(quantity);
                
                $(this).closest('tr').find('.final-price').text(final_price);
            });

            /* below function for generate the invoice and store data */
            $(document).on('submit', '#cartFrm', function(event){
                event.preventDefault();
                let formData = $(this).serializeArray();
                $.ajax({
                    url: '{{ route('invoice.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#submitBtn').prop('disabled', true);
                    },
                    success: function(result){
                        if(result.status == 'success'){
                            successMessage(result.message);
                            $('#cartFrm').trigger('reset');
                            $('#customer').val('').change();
                            $('#table-body').empty();
                            $('#table-foot').empty();
                            $('#balance').empty();
                        }
                        else{
                            showError(result.message);
                            $('#submitBtn').prop('disabled', false);
                        }
                    },
                    error: function(err){
                        errorMessage(err);
                        $('#submitBtn').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection