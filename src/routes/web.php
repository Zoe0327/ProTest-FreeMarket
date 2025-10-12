<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ProfileController;



Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login.do');

//Route::get('/purchases/{item_id}', [PurchaseController::class, 'confirm'])->name('purchase.confirm');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.edit');
Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.update');

Route::get('/profile/address', [ProfileController::class, 'editAddress'])->name('profile.address.edit');
Route::post('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchase.create');
