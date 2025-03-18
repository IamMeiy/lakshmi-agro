<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductVariantController extends Controller
{
    public function index($id){
        $product = Product::with('category')->find($id);
        if($product){
            return view('product-variant', compact('product'));
        }
        else{
            return abort(404, 'Page not found');
        }
    }

    /* below function get the data from Db */
    public function getData($id){
        $variants = ProductVariant::where('product_id',$id)->get();
        return DataTables::of($variants)->make(true);
    }

    /* below fucntion store the data */
    public function store(Request $request){
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'mrp' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        try {   
            $variant = ProductVariant::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'mrp' => $request->mrp,
                'price' => $request->price,
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Variant Created!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }
    
        return response()->json($message);
    }

    /* below function get the edit data */
    public function edit($id){
        $variant = ProductVariant::find($id);
        if($variant){
            return response()->json($variant);
        }
    }

    /* below fucntion update the data */
    public function update(Request $request){
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'mrp' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        $variant = ProductVariant::find($request->variant_id);
        try {   
            $variant->update([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'mrp' => $request->mrp,
                'price' => $request->price,
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Variant Updated!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }
    
        return response()->json($message);
    }

    /* below function for delete the data */
    public function delete($id){
        $variant = ProductVariant::find($id);
        if($variant){
            $variant->deleted_by = Auth::id();
            $variant->save();
            $variant->delete();
            $message = ['status' => 'success', 'message' => 'Variant Deleted!'];
        }
        else{
            $message = ['status' => 'error', 'message' => 'Variant Not Deleted !'];
        }
        return response()->json($message);
    }

    /*  below code for to get variant details */
    public function getVariant(Request $request){
        $variant = ProductVariant::with('product')->find($request->variant_id);
        if($variant){
            return response()->json($variant);
        }
        else{
            return response()->json(['status' => 'error', 'message'=> "Can't find Product"]);
        }
    }
}
