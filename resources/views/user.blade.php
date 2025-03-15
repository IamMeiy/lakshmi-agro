@extends('layouts.master')

@section('title', 'Users')

@section('content')
{{-- Below Code for the Card Content--}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Users</h6>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUser">Add</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="userTable">
                <thead>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>ACTION</th>
                </thead>
            </table>
        </div>
    </div>

{{-- Below code for to Add User Modal Box --}}
    <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserLabel">Add User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="addUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Id</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Re Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
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
    
{{-- Below code for to Edit User Modal Box --}}
    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel">Edit User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
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
            let userTable = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.table') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { 
                        data: 'id', 
                        name: 'id', 
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-primary edit-btn" data-id="${row.id}" data-toggle="modal" data-target="#editUser">Edit</button>
                                <button class="btn btn-danger delete-btn" data-id="${row.id}">Delete</button>
                            `;
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: 3,    // 'action' column (index 3)
                        orderable: false, // Make sure the action column is not sortable
                        searchable: false // Make sure the action column is not searchable
                    }
                ]
            });

            /* Below code for to store the data */
            $(document).on('submit', '#addUserForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('user.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#submitBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#submitBtn').attr('disabled', false);
                        userTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#addUserForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#addUser').modal('hide');
                    },
                    error: function(err){
                        $('#submitBtn').attr('disabled', false);
                        errorMessage(err);
                    }
                });
            });

            /* Below code for to edit the user data */
            $(document).on('click', '.edit-btn', function(event){
                event.preventDefault();
                $('#editUserForm').trigger('reset');
                let dataId = $(this).data('id');
                let editUrl = "{{ route('user.edit', ':id') }}";
                let url = editUrl.replace(':id', dataId);
                if(dataId){
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(result){
                            if(result){
                                $('#user_id').val(result.id);
                                $('#editName').val(result.name);
                                $('#editEmail').val(result.email);
                            }
                        }
                    });
                }
            });
            
            /* Below code for to update the data */
            $(document).on('submit', '#editUserForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('user.update') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#updateBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#updateBtn').attr('disabled', false);
                        userTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#editUserForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#editUser').modal('hide');
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
                let deleteUrl = "{{ route('user.delete', ':id') }}";
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
                                userTable.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection