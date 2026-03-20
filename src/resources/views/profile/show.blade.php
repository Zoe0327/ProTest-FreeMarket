@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/show.css')}}">
@endsection

@section('content')
<div class="mypage__content">
    <div class="mypage__area">
        <div class="mypage__img-circle">
            <img src="{{ $user->profile?->profile_img_url ? asset('storage/' . $user->profile->profile_img_url) : asset('storage/item_images/default_profile.png') }}" alt="プロフィール画像">
        </div>

        <div class="mypage__user">
            <p>{{ $user->name }}</p>

            @if($reviewCount > 0)
                @php
                    $roundedRating = round($averageRating ?? 0);
                @endphp

                <div class="mypage__rating">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $roundedRating)
                            <span class="mypage__star mypage__star--filled">★</span>
                        @else
                            <span class="mypage__star">★</span>
                        @endif
                    @endfor
                </div>
            @endif
        </div>

        <div class="mypage__edit">
            <a href="{{ route('mypage.edit') }}" class="edit__button-submit">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage-form">
        <div class="mypage-form__page">
            <a href="javascript:void(0)" class="tab-link active" data-target="sell">出品した商品</a>
            <a href="javascript:void(0)" class="tab-link" data-target="buy">購入した商品</a>
            <a href="javascript:void(0)" class="tab-link" data-target="trading">
                取引中の商品
                @if($totalUnreadCount > 0)
                    <span class="mypage-tab__badge">{{ $totalUnreadCount }}</span>
                @endif
            </a>
        </div>

        <div id="sell" class="mypage-items tab-content active">
            @foreach ($user->items as $item)
                <div class="mypage-item__each">
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
                        <img src="{{ asset('storage/item_images/' . $item->item_img_url) }}" alt="{{ $item->name }}">
                    </a>
                    <p>{{ $item->name }}</p>
                </div>
            @endforeach
        </div>

        <div id="buy" class="mypage-items tab-content">
            @foreach ($user->soldItems as $soldItem)
                <div class="mypage-item__each">
                    <a href="{{ route('items.show', ['item_id' => $soldItem->item->id]) }}">
                        <img src="{{ asset('storage/item_images/' . $soldItem->item->item_img_url) }}" alt="{{ $soldItem->item->name }}">
                    </a>
                    <p>{{ $soldItem->item->name }}</p>
                </div>
            @endforeach
        </div>

        <div id="trading" class="mypage-items tab-content">
            @if ($inProgressTransactions->isEmpty())
                <p class="mypage-empty">取引中の商品はありません</p>
            @else
                @foreach ($inProgressTransactions as $transaction)
                    <div class="mypage-item__each mypage-item__each--trading">
                        <a href="{{ route('transactions.show', $transaction->id) }}">
                            @if($transaction->unread_count > 0)
                                <span class="mypage-item__badge">{{ $transaction->unread_count }}</span>
                            @endif

                            <img src="{{ asset('storage/item_images/' . $transaction->item->item_img_url) }}" alt="{{ $transaction->item->name }}">
                        </a>
                        <p>{{ $transaction->item->name }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            const target = this.dataset.target;
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(target).classList.add('active');
        });
    });
});
</script>
@endsection