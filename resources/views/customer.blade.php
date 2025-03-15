@extends('layouts.master')

@section('title', 'Customers')

@section('content')
{{-- Below Code for the Card Content--}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Customers</h6>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCustomer">Add</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="customerTable">
                <thead>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>MOBILE</th>
                    <th>EMAIL</th>
                    <th>CUSTOMER TYPE</th>
                    <th>ACTION</th>
                </thead>
            </table>
        </div>
    </div>

{{-- Below code for to Add Customer Modal Box --}}
    <div class="modal fade" id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="addCustomerLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerLabel">Add Customer</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="addCustomerForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="mobile">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Id <span>(optional)</span> </label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="isFarmer" class="form-check-input" name="farmer">
                            <label for="isFarmer" class="form-check-label">Farmer</label>
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
    
{{-- Below code for to Edit Customer Modal Box --}}
    <div class="modal fade" id="editCustomer" tabindex="-1" role="dialog" aria-labelledby="editCustomerLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerLabel">Edit Customer</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editCustomerForm">
                    @csrf
                    <input type="hidden" name="cust_id" id="cust_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="editMobile" name="mobile">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Id <span>(optional)</span> </label>
                            <input type="text" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="editFarmer" class="form-check-input" name="farmer">
                            <label for="editFarmer" class="form-check-label">Farmer</label>
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
            let customerTable = $('#customerTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer.table') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'email', name: 'email' },
                    { 
                        data: 'customer_type', 
                        name: 'customer_type',
                        render: function(data, type, row) {
                            return data === 1 ? 'Farmer' : 'Other';
                        }
                    },
                    { 
                        data: 'id', 
                        name: 'id', 
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-primary edit-btn" data-id="${row.id}" data-toggle="modal" data-target="#editCustomer">Edit</button>
                                <button class="btn btn-danger delete-btn" data-id="${row.id}">Delete</button>
                            `;
                        }
                    }
                ],
                columnDefs: [
                    {
                        targets: 4,    // 'customer_type' column (index 4)
                        searchable: false
                    },
                    {
                        targets: 5,    // 'action' column (index 5)
                        orderable: false, // Make sure the action column is not sortable
                        searchable: false // Make sure the action column is not searchable
                    }
                ]
            });

            /* Below code for to store the data */
            $(document).on('submit', '#addCustomerForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('customer.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#submitBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#submitBtn').attr('disabled', false);
                        customerTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#addCustomerForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#addCustomer').modal('hide');
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
                $('#editCustomerForm').trigger('reset');
                let dataId = $(this).data('id');
                let editUrl = "{{ route('customer.edit', ':id') }}";
                let url = editUrl.replace(':id', dataId);
                if(dataId){
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(result){
                            if(result){
                                $('#cust_id').val(result.id);
                                $('#editName').val(result.name);
                                $('#editMobile').val(result.mobile);
                                $('#editEmail').val(result.email);
                                if(result.customer_type == 1){
                                    $('#editFarmer').attr('checked', true);
                                }
                                else{
                                    $('#editFarmer').attr('checked', false);

                                }
                            }
                        }
                    });
                }
            });
            
            /* Below code for to update the data */
            $(document).on('submit', '#editCustomerForm', function(event){
                event.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('customer.update') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function(){
                        $('#updateBtn').attr('disabled', true);
                    },
                    success: function(res){
                        $('#updateBtn').attr('disabled', false);
                        customerTable.ajax.reload();
                        if(res.status == 'success'){
                            successMessage(res.message);
                            $('#editCustomerForm').trigger('reset');
                        }
                        else{
                            showError(res.message);
                        }
                        $('#editCustomer').modal('hide');
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
                let deleteUrl = "{{ route('customer.delete', ':id') }}";
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
                                customerTable.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection