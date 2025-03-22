<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Purchase::class);
            $table->foreignIdFor(\App\Models\ProductVariant::class);
            $table->integer('previous_stock');  // Stock before purchase
            $table->integer('purchased_stock');  // Newly purchased quantity
            $table->integer('balance_stock');  // Stock after purchase
            $table->decimal('purchase_price', 10, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
