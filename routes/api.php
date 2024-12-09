<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([ 'middleware' => 'api', 'prefix'=> 'auth'], function($router){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group([ 'middleware' => 'jwt.verify'], function($router){
    Route::get('/sections', [SectionController::class, 'index']);
    Route::get('/section', [SectionController::class, 'show']);
    Route::post('/section', [SectionController::class, 'store']);
    Route::put('/section', [SectionController::class, 'update']);
    Route::delete('/section', [SectionController::class, 'destroy']);

    Route::get('/products', [ProductController::class, 'index']);
Route::get('/product', [ProductController::class, 'show']);
Route::post('/product', [ProductController::class, 'store']);
Route::put('/product', [ProductController::class, 'update']);
Route::delete('/product', [ProductController::class, 'destroy']);

Route::get('/invoices', [InvoiceController::class, 'index']);
Route::get('/invoice', [InvoiceController::class, 'show']);
Route::post('/invoice', [InvoiceController::class, 'store']);
Route::put('/invoice', [InvoiceController::class, 'update']);
Route::delete('/invoice', [InvoiceController::class, 'destroy']);
Route::get('/invoices_archive', [InvoiceController::class, 'archive']);
Route::put('/restore', [InvoiceController::class, 'restore']);

Route::post('/invoice_attachment', [InvoiceAttachmentController::class, 'store']);
Route::delete('/invoice_attachment', [InvoiceAttachmentController::class, 'destroy']);


});


