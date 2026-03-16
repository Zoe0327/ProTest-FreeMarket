@extends('layouts.auth')
@section('css')
<link rel="stylesheet" href="{{ asset('css/transactions/show.css')}}">
@endsection

@section('content')
<div class="transaction-container">
    <aside class="transaction-sidebar">
        その他の取引
    </aside>

    <main class="transaction-main">
        <div class="transaction-header">
            <div class="transaction-header-user">
                <img src="" alt="ユーザー画像">
                <h1>「{{ $partner->name }}」さんとの取引画面</h1>
            </div>

            <button class="transaction-complete-button">取引を完了する</button>
        </div>

        <div class="transaction-item">
            <img src="{{ $soldItem->item->item_img_url }}" alt="商品画像">
            <div class="transaction-item-info">
                <h2>{{ $soldItem->item->name }}</h2>
                <p>￥{{ number_format($soldItem->item->price) }}</p>
            </div>
        </div>
        <div class="transaction-chat">
            @foreach($soldItem->messages as $message)

                <div class="chat-message">
                    {{ $message->message }}
                </div>
            @endforeach
        </div>
    </main>
</div>

@endsection