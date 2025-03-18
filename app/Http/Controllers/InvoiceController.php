<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(){
        return view('Billing.index');
    }

    public function create(){
        $customers = Customer::all();
        $variants = ProductVariant::with('product')->get();
        return view('Billing.create_bill', compact('customers', 'variants'));
    }
}
