@extends('layouts.master')

@section('title', 'Dashboard')


@section('content')

    <style>
        .dashboard-card {
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            overflow: hidden;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .card-body {
            padding: 12px; /* Reduced padding */
            text-align: center;
        }
        .card-icon {
            font-size: 30px; /* Reduced icon size */
            opacity: 0.8;
            margin-bottom: 8px;
        }
        .card-title {
            font-size: 14px; /* Smaller title */
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-text {
            font-size: 12px; /* Smaller text */
            opacity: 0.9;
        }
        h3 {
            font-size: 18px; /* Adjusted heading size */
        }

        .bg-gradient-primary { background: linear-gradient(135deg, #007bff, #0056b3); }
        .bg-gradient-success { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .bg-gradient-warning { background: linear-gradient(135deg, #ffc107, #d39e00); }
        .bg-gradient-danger { background: linear-gradient(135deg, #dc3545, #bd2130); }
        .bg-gradient-info { background: linear-gradient(135deg, #17a2b8, #138496); }
        
    </style>

    <div class="container mt-4">
        <div class="row">
            @php
                $total_amount = $sales->sum('final_price');
                $income = $sales->sum('amount_paid');
                $today_sales = $invoices->where('created_at', '>=', today()->startOfDay())
                            ->where('created_at', '<=', today()->endOfDay())
                            ->sum('final_price');
                $balances = $invoices->where('balance_amount', '>', 0)->sum('balance_amount');
            @endphp

            <div class="col-md-6 mb-4">
                <div class="row">
                    {{-- Today's Stats --}}
                    <div class="col-md-12 my-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">Today's Stats</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ([
                                        ['title' => 'New Orders', 'count' => $invoices->filter(fn($invoice) => $invoice->created_at->toDateString() === date('Y-m-d'))->count(), 'icon' => 'box-open', 'bg' => 'success', 'text' => 'Today'],
                                        ['title' => 'Sales', 'count' => '₹' . number_format($today_sales, 2), 'icon' => 'wallet', 'bg' => 'info', 'text' => 'Today']
                                    ] as $card)
                                        <div class="col-md-6 mb-4">
                                            <div class="card text-white bg-gradient-{{ $card['bg'] }} dashboard-card">
                                                <div class="card-body">
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
                        </div>
                    </div>
                    
                    {{-- Financial Stats --}}
                    <div class="col-md-12 my-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">Financial Stats</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ([
                                        ['title' => 'Total Sales', 'count' => '₹' . number_format($total_amount, 2), 'icon' => 'wallet', 'bg' => 'primary', 'text' => 'Last 30 Days'],
                                        ['title' => 'Total Income', 'count' => '₹' . number_format($income, 2), 'icon' => 'wallet', 'bg' => 'success', 'text' => 'Last 30 Days'],
                                        ['title' => 'Pending Payments', 'count' => '₹' . number_format($balances, 2), 'icon' => 'wallet', 'bg' => 'danger', 'text' => 'Last 30 Days'],
                                        ['title' => 'Purchased', 'count' => '₹' . number_format($purchased->sum('total_amount'), 2), 'icon' => 'wallet', 'bg' => 'info', 'text' => 'Last 30 Days']
                                    ] as $card)
                                        <div class="col-md-6 mb-4">
                                            <div class="card text-white bg-gradient-{{ $card['bg'] }} dashboard-card">
                                                <div class="card-body">
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
                        </div>
                    </div>
                </div>  
            </div>

            <div class="col-md-6 mb-4">
                <div class="row">
                    {{-- Overall Stats --}}
                    <div class="col-md-12 my-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">Overall Stats</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ([
                                        ['title' => 'Total Orders', 'count' => count($sales), 'icon' => 'shopping-cart', 'bg' => 'primary', 'text' => 'Last 30 days'],
                                        ['title' => 'Customers', 'count' => count($customers), 'icon' => 'users', 'bg' => 'warning', 'text' => 'Total Customers'],
                                        ['title' => 'Pending Orders', 'count' => $invoices->where('balance_amount', '!=', 0)->count(), 'icon' => 'box-open', 'bg' => 'danger', 'text' => 'Overall']
                                    ] as $card)
                                        <div class="col-md-6 mb-4">
                                            <div class="card text-white bg-gradient-{{ $card['bg'] }} dashboard-card">
                                                <div class="card-body">
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
                        </div>
                    </div>
                    {{-- Inventory Stats --}}
                    <div class="col-md-12 my-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">Stock Stats</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ([
                                        ['title' => 'Low Stocks', 'count' => $variants->where('stock_quantity', '<', 10)->count(), 'icon' => 'box-open', 'bg' => 'warning', 'text' => 'Overall'],
                                        ['title' => 'No Stocks', 'count' => $variants->where('stock_quantity', '==', 0)->count(), 'icon' => 'box-open', 'bg' => 'danger', 'text' => 'Overall']
                                    ] as $card)
                                        <div class="col-md-6 mb-4">
                                            <div class="card text-white bg-gradient-{{ $card['bg'] }} dashboard-card">
                                                <div class="card-body">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
