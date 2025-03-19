@extends('layouts.master')

@section('title', 'Invoice')

@section('content')
{{-- Below Code for the Card Content--}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Invoices</h6>
                <a href="{{ route('invoice.create') }}" class="btn btn-primary btn-sm">Create</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="invoiceTable">
                <thead>
                    <th>INVOICE</th>
                    <th>CUSTOMER</th>
                    <th>DATE</th>
                    <th>PAYMENT MODE</th>
                    <th>TOTAL AMOUNT</th>
                    <th>PAID AMOUNT</th>
                    <th>PENDING AMOUNT</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let invoiceTable = $('#invoiceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('invoice.table') }}",
                columns: [
                    { data: 'invoice_number', name: 'invoice_number' },
                    { data: 'customer_name', name: 'customer_name' },
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
                    { data: 'balance_amount', name: 'balance_amount' }
                    
                ],
                columnDefs: [
                  
                ]
            });
        });
    </script>
@endsection