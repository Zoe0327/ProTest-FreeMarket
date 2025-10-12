@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css')}}">
@endsection

@section('content')
<!-- 成功メッセージの表示 -->
@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="profile__form-container">
    <div class="profile__form-title">
        <h1>プロフィール設定</h1>
    </div>
    <form method="POST" action="{{ route('mypage.update') }}" class="profile__form-content" enctype="multipart/form-data">
        @csrf
        <div class="profile__img-area">
            <div class="profile__img-circle">
                <img src="" alt="プロフィール画像">
            </div>
            <label for="profile_img_input" class="profile__img-label">
                画像を選択する
            </label>
            <input type="file" id="profile_img_input" name="profile_img" accept="image/*" style="display: none;">
        </div>

        <div class="profile__form-each">
            <p>ユーザー名</p>
            <input type="text" name="name" value="">
            @error('name')<div class="error-message"></div>@enderror
        </div>

        <div class="profile__form-each">
            <p>郵便番号</p>
            <input type="text" name="post_code" value="">
            @error('post_code')<div class="error-message"></div>@enderror
        </div>

        <div class="profile__form-each">
            <p>住所</p>
            <input type="text" name="address" value="">
            @error('address')<div class="error-message"></div>@enderror
        </div>

        <div class="profile__form-each">
            <p>建物名</p>
            <input type="text" name="building" value="">
            @error('building')<div class="error-message"></div>@enderror
        </div>


        <div class="profile__form-button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

@endsection