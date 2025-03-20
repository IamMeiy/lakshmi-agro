<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index(){
        return view('category');
    }

    /* below fucntion store the data */
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ],
        [
            'name.required' => 'Name is Empty',
        ]);
        
        DB::beginTransaction();
        try {   
            $cust = Category::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Category Created!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }

    /* below function get the data from Db */
    public function getData(){
        $customer = Category::all();
        return DataTables::of($customer)->make(true);
    }

    /* below function get the edit data */
    public function edit($id){
        $category = Category::find($id);
        if($category){
            return response()->json($category);
        }
    }

    /* below function to update the data */
    public function update(Request $request){
        $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ],
        [
            'name.required' => 'Name is Empty',
        ]);
        
        DB::beginTransaction();
        $cust = Category::find($request->cate_id);
        try {   
            $cust->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Category Updated!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }

    /* below function for delete the data */
    public function delete($id){
        $category = Category::find($id);
        if($category){
            $category->delete();
            $message = ['status' => 'success', 'message' => 'Category Deleted!'];
        }
        else{
            $message = ['status' => 'error', 'message' => 'Category Not Deleted !'];
        }
        return response()->json($message);
    }
}
