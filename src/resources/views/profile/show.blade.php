@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/show.css')}}">
@endsection

@section('content')
<div class="mypage__content">
    <form method="GET" action="{{ route('mypage.edit') }}" class="mypage__form-content" enctype="multipart/form-data">
        <div class="mypage__area">
            <div class="mypage__img-circle">
                <img src="{{ $user->profile?->profile_img_url ? asset('storage/' . $user->profile->profile_img_url) : asset('storage/item_images/default_profile.png') }}" alt="プロフィール画像">
            </div>
            <div class="mypage__user">
                <p>{{ $user->name }}</p>
            </div>
            <div class="mypage__edit">
                <button class="edit__button-submit" type="submit">プロフィールを編集</button>
            </div>
        </div>

        <div class="mypage-form">
            <div class="mypage-form__page">
                <a href="javascript:void(0)" class="tab-link active" data-target="sell">出品した商品</a>
                <a href="javascript:void(0)" class="tab-link" data-target="buy">購入した商品</a>
            </div>
            {{-- 出品商品一覧 --}}

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

            {{-- 購入商品一覧 --}}
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
        </div>
    </form>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function() {
            // タブリンクの active クラス切り替え
            document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            // コンテンツの表示切り替え
            const target = this.dataset.target;
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(target).classList.add('active');
        });
    });
});
</script>
