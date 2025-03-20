<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    public function index(){
        return view('Billing.index');
    }

    /* below function will redirect to create invoice page */
    public function create(){
        $customers = Customer::all();
        $variants = ProductVariant::with('product')->get();
        return view('Billing.create_bill', compact('customers', 'variants'));
    }

    /* below function to get the final price of the invoice */
    public function finalPrice(Request $request){
        $request->validate([
            'customer' => 'required',
            'products.*' => 'required',
            'quantity.*' => 'required|numeric|min:1'
        ],
        [
            'quantity.*.required' => 'Quatity :position is required',
            'quantity.*.numeric' => 'Quatity :position must be number',
            'quantity.*.min' => 'Quatity :position mininum 1',
        ]);

        if(!$request->has("products")){
            return response()->json(['status' => 'error', 'message' => 'Please Add Product']);
        }
        else{
            $products_price = [];
            foreach($request->products as $index => $product){
                $variant = ProductVariant::find($product);
                $products_price[] = $variant->price * $request->quantity[$index];
            }
            return response()->json
                ([
                    'status' => 'success', 
                    'price' => array_sum($products_price), 
                    'quantity' => array_sum($request->quantity)
                ]);
        }
    }

    /* below function to store the data*/
    public function store(Request $request){
        $request->validate
        (
            [
                'customer' => 'required',
                'products.*' => 'required',
                'quantity.*' => 'required|numeric|min:1',
                'payment_type' => 'required',
                'final_price' => 'required|numeric',
                'amount_paid' => 'required',
            ],
            [
                'quantity.*.required' => 'Quatity :position is required',
                'quantity.*.numeric' => 'Quatity :position must be number',
                'quantity.*.min' => 'Quatity :position mininum 1',
            ]
        );

        if(!$request->has("products")){
            return response()->json(['status' => 'error', 'message' => 'Please Add Product']);
        }
        else{
            DB::beginTransaction();
            try {
                $products_price = [];
                foreach($request->products as $index => $product){
                    $variant = ProductVariant::find($product);
                    $products_price[] = $variant->price * $request->quantity[$index];
                }

                /* below code to create invoice number */
                $today = date('Ymd');

                // Get the latest invoice for today
                $lastInvoice = Invoice::
                    whereDate('created_at', Carbon::now('Asia/Kolkata')->toDateString())->orderBy('id', 'desc')->first();

                // Set the new invoice number
                $nextNumber = $lastInvoice ? ((int) str_replace("INV-{$today}-", '', $lastInvoice->invoice_number) + 1) : 1;
                $invoiceNumber = "INV-{$today}-{$nextNumber}";

                $invoice = Invoice::create([
                    'invoice_number' => $invoiceNumber,
                    'customer_id' => $request->customer,
                    'sub_total' => array_sum($products_price),
                    'final_price' => $request->final_price,
                    'amount_paid' => $request->isPaid ? ((!$request->amount_paid) ? $request->final_price : $request->amount_paid) : 0,
                    'balance_amount' => $request->isPaid ? (($request->amount_paid) ? ($request->final_price - $request->amount_paid) : 0) : $request->final_price,
                    'payment_mode' => $request->payment_type
                ]);
                
                foreach($request->products as $index => $product){
                    $product = ProductVariant::find($product);
                    if($product){
                        $invoice->items()->create([
                            'product_variant_id' => $product->id,
                            'quantity' => $request->quantity[$index],
                            'unit_price' => $product->price,
                            'total' => $request->quantity[$index] * $product->price,
                        ]);
                    }
                }

                DB::commit();
                $message = ['status' => 'success', 'message' => 'Invoice Created!'];
            } catch (\Exception $e) {
                DB::rollBack();
                $message = ['status' => 'error', 'message' => $e->getMessage()];
            }
            
            return response()->json($message);
        }
    }

    /* below function to get the invoice data */
    public function getData(){
        $invoices = Invoice::with('customer')->latest();
        return DataTables::of($invoices)
            ->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->name : ''; // Assuming 'name' is a field in the 'customer' table
            })
            ->addColumn('customer_mobile', function ($row) {
                return $row->customer ? $row->customer->mobile : ''; // Assuming 'mobile' is a field in the 'customer' table
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%")
                    ->orWhere('mobile', 'like', "%$keyword%"); // Searching both name and mobile
                });
            })
        ->make(true);

    }

    /* below function to get the particular data to edit */
    public function edit(Request $request){
        if($request->ajax()){
            $invoice = Invoice::with('customer')->find($request->id);
            if($invoice){
                return response()->json(['status' => 'success', 'invoice' => $invoice]);
            }
            else{
                return response()->json(['status' => 'error', 'message' => 'Invoice not found']);
            }
        }
    }

    /* below function to update the bill details */
    public function update(Request $request){
        $request->validate([
            'bill_id' => 'required',
            'payment_type' => 'required',
            'balance_amount' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::find($request->bill_id);
            if($invoice){
                $invoice->update([
                    'amount_paid' => $invoice->amount_paid + $request->balance_amount,
                    'balance_amount' => $invoice->balance_amount - $request->balance_amount,
                    'payment_mode' => $request->payment_type
                ]);

                DB::commit();
                $message = ['status' => 'success', 'message' => 'Invoice Updated!'];
            }
            else{
                DB::rollBack();
                $message = ['status' => 'error', 'message' => 'Ivoice not updated'];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }

    /* below fuction to view particular bill details */
    public function view($id){
        $invoice = Invoice::with('items.variant.product', 'customer')->find($id);
        return view('Billing.view_bill', compact('invoice'));
    }

    /* below function to delete the bill */
    public function delete($id){
        $invoice = Invoice::find($id);
        if($invoice){
            $invoice->delete();
            $message = ['status' => 'success', 'message' => 'Invoice Deleted!'];
        }
        else{
            $message = ['status' => 'error', 'message' => 'Invoice Not Deleted !'];
        }
        return response()->json($message);
    }
}
