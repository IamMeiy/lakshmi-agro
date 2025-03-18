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
                    <th>ID</th>
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