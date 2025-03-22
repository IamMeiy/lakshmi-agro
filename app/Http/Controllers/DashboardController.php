<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ProductVariant;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $date = Carbon::today()->subDays(30);
        $sales = Invoice::where('created_at','>=',$date)->get();
        $customers = Customer::all();
        $invoices = Invoice::all();
        $variants = ProductVariant::all();
        $purchased = Purchase::where('purchase_date','>=', $date)->get();
        return view('dashboard', compact('customers', 'invoices', 'sales', 'variants', 'purchased'));
    }
}
