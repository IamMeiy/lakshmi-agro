<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /* below function return customer page */
    public function index(){
        return view('customer');
    }
    
    /* below fucntion store the data */
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|min:10|unique:'.Customer::class,
            'email' => 'nullable|email',
            'farmer' => 'nullable'
        ],
        [
            'name.required' => 'Name is Empty',
            'mobile.required' => 'Phone Number is Empty',
        ]);
        
        DB::beginTransaction();
        try {   
            $cust = Customer::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'customer_type' => $request->farmer ? 1 : 0
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Customer Created!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }

    /* below function get the data from Db */
    public function getData(){
        $customer = Customer::all();
        return DataTables::of($customer)->make(true);
    }

    /* below function get the edit data */
    public function edit($id){
        $customer = Customer::find($id);
        if($customer){
            return response()->json($customer);
        }
    }

    /* below function to update the data */
    public function update(Request $request){
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|min:10|unique:customers,mobile,'.$request->cust_id,
            'email' => 'nullable|email',
            'farmer' => 'nullable'
        ],
        [
            'name.required' => 'Name is Empty',
            'mobile.required' => 'Phone Number is Empty',
        ]);
        
        DB::beginTransaction();
        $cust = Customer::find($request->cust_id);
        try {   
            $cust->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'customer_type' => $request->farmer ? 1 : 0
            ]);
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Customer Updated!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }

    /* below function for delete the data */
    public function delete($id){
        $customer = Customer::find($id);
        if($customer){
            $customer->deleted_by = Auth::id();
            $customer->save();
            $customer->delete();
            $message = ['status' => 'success', 'message' => 'Customer Deleted!'];
        }
        else{
            $message = ['status' => 'error', 'message' => 'Customer Not Deleted !'];
        }
        return response()->json($message);
    }
}
