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
        <form action="">
            <div class="card-body">
                <div class="row d-flex align-items-center mb-3">
                    <div class="col-md-4">
                        <select name="customer" class="form-control select2" data-placeholder="Select Customer">
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
                                <option value="{{ $variant->id }}">{{ $variant->product->name }} - {{ $variant->quantity }}g</option>
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
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="submitBtn" type="submit">Preview</button>
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
                                    <td>
                                        <input type="hidden" name="products[]">
                                        <input type="number" name="quantity[]" class="form-control" value="1" min="1">
                                    </td>
                                    <td><button class="btn btn-danger remove-btn" type="button">Remove</button></td>
                                </tr>
                                `
                            );
                        }
                    }
                });
                
            });

            /* below function to remove the product from cart */
            $(document).on('click', '.remove-btn', function(event){
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection