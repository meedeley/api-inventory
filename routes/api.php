<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/registrasi', [AuthController::class, 'daftar']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

  Route::apiResource('/kategori', CategoryController::class)->except('create', 'edit');
  Route::apiResource('/supplier', SupplierController::class)->except('create', 'edit');

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
