<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PurchaseController extends Controller
{
    public function index(){
        return view('purchase.index');
    }

    public function create() {
        $products = Product::with('variants')->get();
        return view('purchase.create', compact('products'));
    }

    public function store(Request $request){
        $request->validate([
            'purchase_date' => 'required|date',
            'product_variant_id' => 'required|array',
            'product_variant_id.*' => 'required|exists:product_variants,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'purchase_price' => 'required|array',
            'purchase_price.*' => 'required|numeric|min:0',
        ]);
    
        DB::beginTransaction();
        try {   
            // ✅ Calculate Total Price (Quantity * Purchase Price for each item)
            $total_price = 0;
            foreach ($request->product_variant_id as $index => $variant_id) {
                $total_price += $request->quantity[$index] * $request->purchase_price[$index];
            }
    
            // Create a new purchase record
            $purchase = Purchase::create([
                'total_amount' => $total_price, // ✅ Store the calculated total price
                'purchase_date' => $request->purchase_date, // Use selected date
            ]);
    
            foreach ($request->product_variant_id as $index => $variant_id) {
                $variant = ProductVariant::find($variant_id);
                $previous_stock = $variant->stock_quantity;
                $purchased_stock = $request->quantity[$index];
                $new_balance_stock = $previous_stock + $purchased_stock;
    
                // Store purchase item
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_variant_id' => $variant_id,
                    'previous_stock' => $previous_stock,
                    'purchased_stock' => $purchased_stock,
                    'balance_stock' => $new_balance_stock,
                    'purchase_price' => $request->purchase_price[$index],
                ]);
    
                // Update stock in product_variants
                $variant->stock_quantity = $new_balance_stock;
                $variant->save();
            }
            
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Purchase saved successfully!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }
    
        return response()->json($message);
    }    

    public function getPurchases()
    {
        $purchases = Purchase::select(['id', 'purchase_date', 'total_amount'])->latest();

        return DataTables::of($purchases)
            ->addColumn('actions', function ($purchase) {
                return '
                    <a href="'.route('purchase.show', $purchase->id).'" class="btn btn-sm btn-info">View</a>
                    <a href="'.route('purchase.edit', $purchase->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$purchase->id.'">Delete</button>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show($id) {
        $purchase = Purchase::with('items.productVariant.product')->findOrFail($id);
        return view('purchase.show', compact('purchase'));
    }    
    
    public function edit($id) {
        $purchase = Purchase::with('items.productVariant.product')->findOrFail($id);
        $products = Product::with('variants')->get();
        return view('purchase.edit', compact('purchase', 'products'));
    }

    public function update(Request $request) {
        $request->validate([
            'purchase_date' => 'required|date',
            'item_id' => 'nullable|array', // Existing purchase item IDs
            'product_variant_id' => 'required|array',
            'product_variant_id.*' => 'required|exists:product_variants,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'purchase_price' => 'required|array',
            'purchase_price.*' => 'required|numeric|min:0',
        ]);
    
        DB::beginTransaction();
        try {
            $purchase = Purchase::findOrFail($request->purchase_id);
            $total_price = 0;
    
            // ✅ Get existing purchase item IDs from the DB
            $existingItemIds = $purchase->items->pluck('id')->toArray();
            $submittedItemIds = $request->item_id ?? [];
    
            // ✅ Identify items to delete (present in DB but not in request)
            $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
            foreach ($itemsToDelete as $itemId) {
                $item = PurchaseItem::find($itemId);
                if ($item) {
                    // ✅ Restore stock before deleting the item
                    $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
                    if ($variant) {
                        $variant->stock_quantity = max(0, $variant->stock_quantity - $item->purchased_stock); // Prevent negative stock
                        $variant->save();
                    }
                    $item->delete();
                }
            }
    
            foreach ($request->product_variant_id as $index => $variant_id) {
                $purchased_stock = $request->quantity[$index];
                $purchase_price = $request->purchase_price[$index];
    
                $variant = ProductVariant::lockForUpdate()->find($variant_id);
    
                if (isset($request->item_id[$index])) {
                    // ✅ Update existing item
                    $purchaseItem = PurchaseItem::find($request->item_id[$index]);
    
                    if ($purchaseItem->product_variant_id != $variant_id) {
                        // ✅ If product is changed, restore stock of old product
                        $oldVariant = ProductVariant::lockForUpdate()->find($purchaseItem->product_variant_id);
                        if ($oldVariant) {
                            $oldVariant->stock_quantity = max(0, $oldVariant->stock_quantity - $purchaseItem->purchased_stock);
                            $oldVariant->save();
                        }
                    }
    
                    // ✅ Subtract old stock before updating
                    $variant->stock_quantity = max(0, $variant->stock_quantity - $purchaseItem->purchased_stock);
    
                    // ✅ Update purchase item with new values
                    $purchaseItem->update([
                        'product_variant_id' => $variant_id, // Allow product change
                        'purchased_stock' => $purchased_stock,
                        'balance_stock' => $variant->stock_quantity + $purchased_stock,
                        'purchase_price' => $purchase_price,
                    ]);
                } else {
                    // ✅ Create new purchase item
                    $purchaseItem = PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_variant_id' => $variant_id,
                        'previous_stock' => $variant->stock_quantity,
                        'purchased_stock' => $purchased_stock,
                        'balance_stock' => $variant->stock_quantity + $purchased_stock,
                        'purchase_price' => $purchase_price,
                    ]);
                }
    
                // ✅ Update stock with new quantity
                $variant->stock_quantity = $purchaseItem->balance_stock;
                $variant->save();
    
                // ✅ Calculate total price
                $total_price += $purchased_stock * $purchase_price;
            }
    
            // ✅ Update purchase record
            $purchase->update([
                'total_amount' => $total_price,
                'purchase_date' => $request->purchase_date,
            ]);
    
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Purchase updated successfully!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }

        return response()->json($message);
    }    
    
    public function destroy($id) {
        DB::beginTransaction();
        try {
            $purchase = Purchase::findOrFail($id);
            $purchaseItems = $purchase->items;
    
            // ✅ Loop through purchase items and subtract stock
            foreach ($purchaseItems as $item) {
                $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
    
                if ($variant) {
                    // Subtract purchased stock from current stock
                    $variant->stock_quantity = max(0, $variant->stock_quantity - $item->purchased_stock);
                    $variant->save();
                }
    
                // Delete purchase item
                $item->delete();
            }
    
            // ✅ Delete purchase record
            $purchase->delete();
    
            DB::commit();
            $message = ['status' => 'success', 'message' => 'Purchase deleted successfully!'];
        } catch (\Exception $e) {
            DB::rollBack();
            $message = ['status' => 'error', 'message' => $e->getMessage()];
        }
        return response()->json($message);
    }
    
}
