<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;



Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item_id}', [ItemController::class, 'show'])->name('items.show');

// 認証済みユーザーのみアクセス可能なルート
Route::middleware(['auth'])->group(function () {
    // マイページ・プロフィール関連
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.update');

    // 出品関連
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    // 購入関連
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    // 確認用
    //Route::get('/profile/address', [ProfileController::class, 'editAddress'])->name('profile.address.edit');
    //Route::post('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::get('/purchases/create/{item_id}', [PurchaseController::class, 'create'])->name('purchase.create');

    Route::post('/items/{item_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/items/{item}/like', [ItemController::class, 'toggleLike'])->middleware('auth')->name('items.like');
});
