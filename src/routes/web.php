<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReviewController;



Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item_id}', [ItemController::class, 'show'])->name('items.show');

// ゲストユーザーのみアクセス可能なルート（ログイン・会員登録）
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');



// 認証済みユーザーのみアクセス可能なルート
Route::middleware(['auth'])->group(function () {
    // マイページ・プロフィール関連
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('mypage.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.update');

    // 出品関連
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    // 購入関連
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchases.address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchases.address.update');

    Route::get('/purchases/create/{item_id}', [PurchaseController::class, 'create'])->name('purchases.create');

    Route::post('/items/{item_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/items/{item}/like', [ItemController::class, 'toggleLike'])->middleware('auth')->name('items.like');

    Route::get('/transactions/{soldItem}', [TransactionController::class, 'show'])->name('transactions.show');

    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::patch('/messages/{message}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::post('/checkout', [StripeController::class, 'session'])->name('checkout.session');
Route::get('/success', [StripeController::class, 'success'])->name('checkout.success');
Route::get('/cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');
Route::post('/stripe/webhook', [StripeController::class, 'webhook']);

//メール認証通知
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

//メール内リンククリック時の承認処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); //認証完了
    return redirect()->route('mypage.edit');
})->middleware(['auth', 'signed'])->name('verification.verify');


//ダミールート
Route::post('/email/verify/dummy', function () {
    return back();
})->name('verification.dummy');

//認証メール再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    //return redirect('/mypage/profile');
    return back()->with('message', '認証メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
