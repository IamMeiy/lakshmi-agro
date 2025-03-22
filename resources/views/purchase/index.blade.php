@extends('layouts.master')

@section('title', 'Purchases')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6>Purchases</h6>
            <a href="{{ route('purchase.create') }}" class="btn btn-primary btn-sm">New Purchase</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="purchaseTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DATE</th>
                    <th>AMOUNT</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('#purchaseTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('purchase.table') }}", // Fetch data via AJAX
            columns: [
                { data: 'id', name: 'id' },
                { 
                    data: 'purchase_date', 
                    name: 'purchase_date', 
                    render: function(data) {
                        let date = new Date(data);
                        return date.toLocaleDateString('en-GB');
                    }
                },
                { data: 'total_amount', name: 'total_amount', render: function(data) { return '₹' + parseFloat(data).toFixed(2); } },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
    });

    $(document).on('click', '.delete-btn', function(event){
        event.preventDefault();
        let purchaseId = $(this).data('id');
        let deleteUrl = "{{ route('purchase.destroy', ':id') }}";
        let url = deleteUrl.replace(':id', purchaseId);

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
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response){
                        if(response.status == 'success'){
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your purchase has been deleted.",
                                icon: "success"
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error"
                            });
                        }
                        $('#purchaseTable').DataTable().ajax.reload(); // Refresh DataTable
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong. Please try again.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });
</script>

@endsection
