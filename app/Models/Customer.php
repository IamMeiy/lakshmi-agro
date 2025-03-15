<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, SoftDeletes;

    /* to define which dont wnat to add or insert */
    protected $guarded = [];

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
            }
        });
    }
}
