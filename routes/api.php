<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionInController;
use App\Http\Controllers\TransactionOutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/registrasi', [AuthController::class, 'daftar']);
Route::post('/login', [AuthController::class, 'login']);

  Route::post('/logout', [AuthController::class, 'logout']);

  Route::apiResource('/kategori', CategoryController::class)->except('create', 'edit');
  Route::apiResource('/supplier', SupplierController::class)->except('create', 'edit');
  Route::apiResource('/barang', ItemController::class)->except('create', 'edit');
  Route::apiResource('/customer', CustomerController::class)->except('create', 'edit');
  Route::apiResource('/barang-masuk', TransactionInController::class)->except('create', 'edit');
  Route::apiResource('/barang-keluar', TransactionOutController::class)->except('create', 'edit');
  
Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');
