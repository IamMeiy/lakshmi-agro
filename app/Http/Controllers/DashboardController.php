<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $date = Carbon::today()->subDays(30);
        $sales = Invoice::where('created_at','>=',$date)->get();
        $customers = Customer::all();
        $invoices = Invoice::all();
        return view('dashboard', compact('customers', 'invoices', 'sales'));
    }
}
