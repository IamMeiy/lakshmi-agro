<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/',[DashboardController::class, 'index'])->name('dashboard');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/table', [CustomerController::class, 'getData'])->name('customer.table');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('/customer/update', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
    Route::get('/customer/view/{id}', [CustomerController::class, 'view'])->name('customer.view');
    Route::get('/customer/bills/{id}', [CustomerController::class, 'getBills'])->name('customer.bills');
    
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/data', [UserController::class, 'getData'])->name('user.table');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/table', [CategoryController::class, 'getData'])->name('category.table');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/category/update', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/category/delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');

    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/table', [ProductController::class, 'getData'])->name('product.table');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');

    Route::get('/product-variant/{id}', [ProductVariantController::class, 'index'])->name('variant.index');
    Route::get('/variant/table/{id}', [ProductVariantController::class, 'getData'])->name('variant.table');
    Route::post('/variant/store', [ProductVariantController::class, 'store'])->name('variant.store');
    Route::get('/variant/edit/{id}', [ProductVariantController::class, 'edit'])->name('variant.edit');
    Route::post('/variant/update', [ProductVariantController::class, 'update'])->name('variant.update');
    Route::get('/variant/delete/{id}', [ProductVariantController::class, 'delete'])->name('variant.delete');
    Route::get('/get-variant', [ProductVariantController::class, 'getVariant'])->name('get.variant');
    
    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/table', [InvoiceController::class, 'getData'])->name('invoice.table');
    Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::get('/invoice/getFinalPrice', [InvoiceController::class, 'finalPrice'])->name('invoice.finalPrice');
    Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/editBill', [InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::post('/invoice/updateBill', [InvoiceController::class, 'update'])->name('invoice.update');
    Route::get('/invoice/viewBill/{id}', [InvoiceController::class, 'view'])->name('invoice.viewBill');
    Route::get('/invoice/delete/{id}', [InvoiceController::class, 'delete'])->name('invoice.delete');
    Route::get('/invoice/download/{id}', [InvoiceController::class, 'downloadPdf'])->name('invoice.download');
    Route::get('/invoice/preview/{id}', [InvoiceController::class, 'previewPdf'])->name('invoice.preview');
    
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/table', [InventoryController::class, 'getData'])->name('inventory.table');
    
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchase.index');
    Route::get('/purchase/create', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/store', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/data', [PurchaseController::class, 'getPurchases'])->name('purchase.table');
    Route::get('/purchase/view/{id}', [PurchaseController::class, 'show'])->name('purchase.show');  // View Purchase
    Route::get('/purchase/edit/{id}', [PurchaseController::class, 'edit'])->name('purchase.edit');  // Edit Purchase
    Route::post('/purchase/update', [PurchaseController::class, 'update'])->name('purchase.update');  // Update Purchase
    Route::delete('/purchase/{id}', [PurchaseController::class, 'destroy'])->name('purchase.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
