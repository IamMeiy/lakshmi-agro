@extends('layouts.master')

@section('title', 'Customer Bills')

@section('content')
{{-- Below code for card content --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <th>NAME</th>
                    <th>MOBILE</th>
                    <th>EMAIL</th>
                    <th>CUSTOMER TYPE</th>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->mobile }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->customer_type ? 'Farmer' : 'Other' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>{{$customer->name}} Bills</h6>
                <a href="{{ route('customer.index') }}" class="btn btn-primary btn-sm">Back</a>            
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="billTable">
                <thead>
                    <th style="width: 150px;">INVOICE</th>  <!-- Set width in px -->
                    <th style="width: 150px;">DATE</th>
                    <th style="width: 120px;">PAYMENT MODE</th>
                    <th style="width: 120px;">TOTAL AMOUNT</th>
                    <th style="width: 160px;">PAID AMOUNT</th>
                    <th style="width: 160px;">PENDING AMOUNT</th>
                    <th style="width: 200px;">ACTION</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let billTable = $('#billTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('customer.bills', $id) }}',
                columns: [
                    { data: 'invoice_number', name: 'invoice_number' },
                    { 
                        data: 'created_at', 
                        name: 'created_at',
                        render: function(data, type, row, meta) {
                            let date = new Date(data);
                            // Format the date to show only the date part (YYYY-MM-DD)
                            let formattedDate = date.toLocaleDateString(); // This will display the date part

                            // Format the time to show only the time part (HH:mm:ss)
                            let formattedTime = date.toLocaleTimeString(); // This will display the time part

                            // Combine the date and time into one string
                            return formattedDate + ' ' + formattedTime;
                        }
                    },
                    { data: 'payment_mode', name: 'payment_mode' },
                    { data: 'final_price', name: 'final_price' },
                    { data: 'amount_paid', name: 'amount_paid' },
                    { data: 'balance_amount', name: 'balance_amount' },
                    { 
                        data: 'id', 
                        name: 'id',
                        render: function(data, type, row, meta){
                            let action = '';
                            let invoiceViewUrl = "{{ route('invoice.viewBill', ['id' => '_id_']) }}";
                            let url = invoiceViewUrl.replace('_id_', data);
                            action += '<a href="'+ url +'" class="btn btn-success">View</a>';
                            if(row.balance_amount != 0){
                                action += '<button class="btn btn-primary ml-2" onClick="editBillDetails('+ data +')">Edit</button>'; 
                            }
                            action += '<button class="btn btn-danger ml-2" onClick="deleteBill('+ data +')">Delete</button>';

                            return action;
                        }
                    }
                    
                ],
                columnDefs: [
                  {
                    target: 6,
                    orderable: false, // Make sure the action column is not sortable
                    searchable: false // Make sure the action column is not searchable
                  }
                ]
            });
        });
    </script>
@endsection