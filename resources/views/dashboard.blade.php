@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<style>
    .dashboard-card {
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        overflow: hidden;
    }
    .dashboard-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    .card-body {
        padding: 25px;
    }
    .card-icon {
        font-size: 50px;
        opacity: 0.8;
        margin-bottom: 15px;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff, #004085); }
    .bg-gradient-success { background: linear-gradient(135deg, #28a745, #155724); }
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107, #856404); }
    .bg-gradient-danger { background: linear-gradient(135deg, #dc3545, #721c24); }
    .card-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .card-text {
        font-size: 14px;
        opacity: 0.9;
    }
</style>

<div class="container mt-4">
    <div class="row">
        @php
            $total_amount = $sales->sum('final_price');
            $income = $sales->sum('amount_paid');
            $balances = $invoices->where('balance_amount', '>', 0)->sum('balance_amount');
        @endphp


        @foreach ([
            ['title' => 'Total Orders', 'count' => count($sales), 'icon' => 'shopping-cart', 'bg' => 'primary', 'text' => 'Last 30 days'],
            ['title' => 'New Orders', 'count' => $invoices->filter(fn($invoice) => $invoice->created_at->toDateString() === date('Y-m-d'))->count(), 'icon' => 'box-open', 'bg' => 'success', 'text' => 'Today'],
            ['title' => 'Customers', 'count' => count($customers), 'icon' => 'users', 'bg' => 'warning', 'text' => 'Total Customers'],
            ['title' => 'Pending Orders', 'count' => $invoices->where('balance_amount', '!=', 0)->count(), 'icon' => 'box-open', 'bg' => 'danger', 'text' => 'Overall'],
            ['title' => 'Total Sales', 'count' => '₹' . number_format($total_amount, 2), 'icon' => 'wallet', 'bg' => 'primary', 'text' => 'Last 30 Days'],
            ['title' => 'Total Income', 'count' => '₹' . number_format($income, 2), 'icon' => 'wallet', 'bg' => 'success', 'text' => 'Last 30 Days'],
            ['title' => 'Pending Payments', 'count' => '₹' . number_format($balances, 2), 'icon' => 'wallet', 'bg' => 'danger', 'text' => 'Last 30 Days']
        ] as $card)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card text-white bg-gradient-{{ $card['bg'] }} dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-{{ $card['icon'] }} card-icon"></i>
                        <h5 class="card-title">{{ $card['title'] }}</h5>
                        <h3>{{ $card['count'] }}</h3>
                        <p class="card-text">{{ $card['text'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
