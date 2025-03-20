@extends('layouts.master')

@section('title', 'Category')

@section('content')
{{-- Below Code for the Card Content--}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Categories</h6>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCategory">Add</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="categoryTable">
                <thead>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>TAX</th>
                    <th>DESCRIPTION</th>
                    <th>ACTIONS</th>
                </thead>
            </table>
        </div>
    </div>

{{-- Below code for to Add Category Modal Box --}}
    <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add Category</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="addCategoryForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tax</label>
                            <input type="text" class="form-control" name="tax">
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
    
{{-- Below code for to Edit Category Modal Box --}}
    <div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="editCategoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editCategoryForm">
                    @csrf
                    <input type="hidden" name="cate_id" id="cate_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" id="editName" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tax</label>
                            <input type="text" id="editTax" class="form-control" name="tax">
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
            let categoryTable = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('category.table') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'tax', name: 'tax' },
                    { data: 'description', name: 'description' },
                    { 
                        data: 'id', 
                        name: 'id', 
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-primary edit-btn" data-id="${row.id}" data-toggle="modal" data-target="#editCategory">Edit</button>
                                <button class="btn btn-danger delete-btn" data-id="${row.id}">Delete</button>
                            `;
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: 4,    // 'action' column (index 5)
                        orderable: false, // Make sure the action column is not sortable
                        searchable: false // Make sure the action column is not searchable
                    }
                ]
            });

            /* Below code for to store the data */
            $(document).on('submit', '#addCategoryForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('category.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#submitBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#submitBtn').attr('disabled', false);
                        categoryTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#addCategoryForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#addCategory').modal('hide');
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
                $('#editCategoryForm').trigger('reset');
                let dataId = $(this).data('id');
                let editUrl = "{{ route('category.edit', ':id') }}";
                let url = editUrl.replace(':id', dataId);
                if(dataId){
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(result){
                            if(result){
                                $('#cate_id').val(result.id);
                                $('#editName').val(result.name);
                                $('#editTax').val(result.tax);
                                $('#editDescription').val(result.description);
                            }
                        }
                    });
                }
            });

            /* Below code for to update the data */
            $(document).on('submit', '#editCategoryForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('category.update') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#updateBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#updateBtn').attr('disabled', false);
                        categoryTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#editCategoryForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#editCategory').modal('hide');
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
                let deleteUrl = "{{ route('category.delete', ':id') }}";
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
                                categoryTable.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection