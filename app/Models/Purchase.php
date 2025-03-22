<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;


class Purchase extends Model {
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['total_amount', 'purchase_date'];

    public function items() {
        return $this->hasMany(PurchaseItem::class);
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
