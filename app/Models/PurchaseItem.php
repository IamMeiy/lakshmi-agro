<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PurchaseItem extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_id', 
        'product_variant_id', 
        'previous_stock', 
        'purchased_stock', 
        'balance_stock', 
        'purchase_price'
    ];

    public function productVariant() {
        return $this->belongsTo(ProductVariant::class,'product_variant_id');
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically set 'created_by' when creating a new customer
        static::creating(function ($customer) {
            if (Auth::check()) {
                $customer->created_by = Auth::id(); // Set the currently authenticated user's ID
            }
        });

        // Automatically set 'modified_by' when updating an existing customer
        static::updating(function ($customer) {
            if (Auth::check()) {
                $customer->modified_by = Auth::id(); // Set the currently authenticated user's ID
            }
        });

        // Automatically set 'deleted_by' when performing a soft delete
        static::deleting(function ($customer) {
            if (Auth::check()) {
                $customer->deleted_by = Auth::id(); // Set the currently authenticated user's ID
                $customer->save();
            }
        });
    }
}

