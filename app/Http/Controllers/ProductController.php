<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('product', compact('categories'));
    }

    /* below fucntion store the data */
    public function store(Request $request){
        $request->validate([
            'category' => 'required',
            'name' => 'required',
            'description' => 'nullable'
        ],
        [
            'name.required' => 'Name is Empty',
        ]);
        
        DB::beginTransaction();
        try {   
            $Product = Product::create([
                'category_id' => $request->category,
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Product Created!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }
    
        return response()->json($message);
    }
    
    /* below function get the data from Db */
    public function getData(){
        $Products = Product::with('category')->get();
        return DataTables::of($Products)->make(true);
    }
    
    /* below function get the edit data */
    public function edit($id){
        $Product = Product::find($id);
        if($Product){
            return response()->json($Product);
        }
    }
    
    /* below function to update the data */
    public function update(Request $request){
        $request->validate([
            'category' => 'required',
            'name' => 'required',
            'description' => 'nullable'
        ],
        [
            'category.required' => 'Select the Category',
            'name.required' => 'Name is Empty',
        ]);
        
        DB::beginTransaction();
        $product = Product::find($request->product_id);
        try {   
            $product->update([
                'category_id' => $request->category,
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Product Updated!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }
    
        return response()->json($message);
    }
    
    /* below function for delete the data */
    public function delete($id){
        $Product = Product::find($id);
        if($Product){
            $Product->delete();
            $message = ['status' => 'success', 'message' => 'Product Deleted!'];
        }
        else{
            $message = ['status' => 'error', 'message' => 'Product Not Deleted !'];
        }
        return response()->json($message);
    }
}
