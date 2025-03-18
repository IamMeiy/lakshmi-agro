@extends('layouts.master')

@section('title', 'Product Variant')

@section('content')
{{-- Below Code for the Product Variant Card Content--}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="productTable">
                <thead>
                    <th>PRODUCT NAME</th>
                    <th>CATEGORY</th>
                    <th>DESCRIPTION</th>
                    <th>ACTIONS</th>
                </thead>
                <tbody>
                    <tr>
                        <th>{{ $product->name }}</th>
                        <th>{{ $product->category->name }}</th>
                        <th>{{ $product->description }}</th>
                        <th>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addVariant">Add</button>
                            <a href="{{route('product.index')}}" class="btn btn-info">Back</a>
                        </th>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered" id="variantTable">
                <thead>
                    <th>ID</th>
                    <th>QUANTITY</th>
                    <th>MRP</th>
                    <th>PRICE</th>
                    <th>ACTIONS</th>
                </thead>
            </table>
        </div>
    </div>

{{-- Below code for to Add Variant Modal Box --}}
<div class="modal fade" id="addVariant" tabindex="-1" role="dialog" aria-labelledby="addVariantLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVariantLabel">Add Category</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="addVariantForm">
                @csrf
                <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" name="quantity">
                    </div>
                    <div class="form-group">
                        <label class="form-label">MRP</label>
                        <input type="text" class="form-control" name="mrp">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input type="text" class="form-control" name="price">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="submitBtn" type="submit">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Below code for to Edit Variant Modal Box --}}
<div class="modal fade" id="editVariant" tabindex="-1" role="dialog" aria-labelledby="editVariantLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVariantLabel">Edit Category</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="editVariantForm">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}">
                    <input type="hidden" name="variant_id" id="variant_id" value="">
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" name="quantity" id="edit-quantity">
                    </div>
                    <div class="form-group">
                        <label class="form-label">MRP</label>
                        <input type="text" class="form-control" name="mrp" id="edit-mrp">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input type="text" class="form-control" name="price" id="edit-price">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="updateBtn" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let productVariantRoute = '{{ route("variant.table", ":id") }}';
            let url = productVariantRoute.replace(':id', {{$product->id}});

            let variantTable = $('#variantTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'mrp', name: 'mrp' },
                    { data: 'price', name: 'price' },
                    { 
                        data: 'id', 
                        name: 'id', 
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-primary edit-btn btn-sm" data-id="${row.id}" data-toggle="modal" data-target="#editVariant">Edit</button>
                                <button class="btn btn-danger delete-btn btn-sm" data-id="${row.id}">Delete</button>
                            `;
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: 4,    // 'action' column (index)
                        orderable: false, // Make sure the action column is not sortable
                        searchable: false // Make sure the action column is not searchable
                    }
                ]
            });

            /* Below code for to store the data */
            $(document).on('submit', '#addVariantForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('variant.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#submitBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#submitBtn').attr('disabled', false);
                        variantTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#addvariantForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#addVariant').modal('hide');
                    },
                    error: function(err){
                        $('#submitBtn').attr('disabled', false);
                        errorMessage(err);
                    }
                });
            });

            /* Below code for to edit the customer data */
            $(document).on('click', '.edit-btn', function(event){
                event.preventDefault();
                $('#editvariantForm').trigger('reset');
                let dataId = $(this).data('id');
                let editUrl = "{{ route('variant.edit', ':id') }}";
                let url = editUrl.replace(':id', dataId);
                if(dataId){
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(result){
                            if(result){
                                $('#variant_id').val(result.id);
                                $('#edit-quantity').val(result.quantity);
                                $('#edit-mrp').val(result.mrp);
                                $('#edit-price').val(result.price);
                            }
                        }
                    });
                }
            });

            /* Below code for to update the data */
            $(document).on('submit', '#editVariantForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('variant.update') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#updateBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#updateBtn').attr('disabled', false);
                        variantTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#editvariantForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#editVariant').modal('hide');
                    },
                    error: function(err){
                        $('#updateBtn').attr('disabled', false);
                        errorMessage(err);
                    }
                });
            });

            /* Below code for delete the data */
            $(document).on('click', '.delete-btn', function(event){
                event.preventDefault();
                let dataId = $(this).data('id');
                let deleteUrl = "{{ route('variant.delete', ':id') }}";
                let url = deleteUrl.replace(':id', dataId);
                
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(result){
                                if(result.status == 'success'){
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Your data has been deleted.",
                                        icon: "success"
                                    });
                                }
                                else{
                                    Swal.fire({
                                        title: "Not Deleted!",
                                        text: "Your data hasn't been deleted.",
                                        icon: "error"
                                    });
                                }
                                variantTable.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection