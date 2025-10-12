@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/show.css')}}">
@endsection

@section('content')
<div class="mypage__content">
    <form method="GET" action="{{ route('mypage.edit') }}" class="mypage__form-content" enctype="multipart/form-data">
        <div class="mypage__area">
            <div class="mypage__img-circle">
                <img src="" alt="プロフィール画像">
            </div>
            <div class="mypage__user">
                <p>ユーザー名</p>
            </div>
            <div class="mypage__edit">
                <button class="edit__button-submit" type="submit">プロフィールを編集</button>
            </div>
        </div>

        <div class="mypage-form">
            <div class="mypage-form__page">
                <a>出品した商品</a>
                <a>購入した商品</a>
            </div>
            <div class="mypage-items">
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
            </div>
            <div class="mypage-items">
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
                <div class="mypage-item__each">
                    <img src="" alt="商品画像">
                    <p>商品名</p>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection