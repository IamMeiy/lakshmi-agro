@extends('layouts.master')

@section('title', 'Products')

@section('content')
    {{-- Below Code for the Card Content--}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Products</h6>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProduct">Add</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="productTable">
                <thead>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>CATEGORY</th>
                    <th>DESCRIPTION</th>
                    <th>ACTIONS</th>
                </thead>
            </table>
        </div>
    </div>

{{-- Below code for to Add Product Modal Box --}}
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductLabel">Add Category</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="addProductForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="">Select Category</option>
                                @forelse ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @empty
                                    <div class="text-center">
                                        No Data Available
                                    </div>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description <span>(optional)</span> </label>
                            <input type="text" class="form-control" name="description">
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
    
{{-- Below code for to Edit Product Modal Box --}}
    <div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="editProductLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductLabel">Edit Category</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editProductForm">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control" id="editCategory">
                                <option value="">Select Category</option>
                                @forelse ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @empty
                                    <div class="text-center">
                                        No Data Available
                                    </div>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" id="editName" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description <span>(optional)</span> </label>
                            <input type="text" id="editDescription" class="form-control" name="description">
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
            let productTable = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('product.table') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'category.name', name: 'category-name' },
                    { data: 'description', name: 'description' },
                    { 
                        data: 'id', 
                        name: 'id', 
                        render: function(data, type, row) {
                            let productVariantRoute = '{{ route("variant.index", ":id") }}';
                            let url = productVariantRoute.replace(':id', row.id);
                            return `
                                <a href="${url}" class="btn btn-info btn-sm">Variant</a>
                                <button class="btn btn-primary edit-btn btn-sm" data-id="${row.id}" data-toggle="modal" data-target="#editProduct">Edit</button>
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
            $(document).on('submit', '#addProductForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('product.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#submitBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#submitBtn').attr('disabled', false);
                        productTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#addProductForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#addProduct').modal('hide');
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
                $('#editProductForm').trigger('reset');
                let dataId = $(this).data('id');
                let editUrl = "{{ route('product.edit', ':id') }}";
                let url = editUrl.replace(':id', dataId);
                if(dataId){
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(result){
                            if(result){
                                $('#product_id').val(result.id);
                                $('#editCategory').val(result.category_id);
                                $('#editName').val(result.name);
                                $('#editDescription').val(result.description);
                            }
                        }
                    });
                }
            });

            /* Below code for to update the data */
            $(document).on('submit', '#editProductForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('product.update') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#updateBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#updateBtn').attr('disabled', false);
                        productTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#editProductForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#editProduct').modal('hide');
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
                let deleteUrl = "{{ route('product.delete', ':id') }}";
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
                                productTable.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection