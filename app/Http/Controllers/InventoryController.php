<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventoryController extends Controller
{
    public function index(){
        $products = Product::all();
        return view('inventory', compact('products'));
    }

    public function getData(Request $request) {
        $query = ProductVariant::with('product');
    
        // Filter by Product Name
        if ($request->has('product_name') && $request->product_name != '') {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', $request->product_name);
            });
        }
    
        // Filter by Stock Status
        if ($request->has('stock_filter')) {
            if ($request->stock_filter == 'no_stock') {
                $query->where('stock_quantity', 0);
            } elseif ($request->stock_filter == 'low_stock') {
                $query->where('stock_quantity', '<', 10);
            }
        }
    
        return DataTables::of($query)->make(true);
    }
    
}
