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
    <form method="POST" action="{{ route('mypage.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="profile__img-area">
            <div class="profile__img-circle">
                <img src="{{ $profile?->profile_img_url ? asset('storage/'.$profile->profile_img_url) : asset('storage/item_images/default_profile.png') }}" alt="プロフィール画像">
            </div>
            <label for="profile_img_input" class="profile__img-label">
                画像を選択する
            </label>
            <input type="file" id="profile_img_input" name="profile_img_url" accept="image/*" style="display: none;">
        </div>

        <div class="profile__form-each">
            <p>ユーザー名</p>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
            <div class="error-message">
                @error('name')
                    {{ $message }}
                @enderror
            </div>

        </div>

        <div class="profile__form-each">
            <p>郵便番号</p>
            <input type="text" name="post_code" value="{{ old('post_code', $profile?->post_code ?? '') }}">
            <div class="error-message">
                @error('post_code')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile__form-each">
            <p>住所</p>
            <input type="text" name="address" value="{{ old('address', $profile?->address ?? '') }}">

            <div class="error-message">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="profile__form-each">
            <p>建物名</p>
            <input type="text" name="building" value="{{ old('building', $profile?->building ?? '') }}">
            <div class="error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </div>
        </div>


        <div class="profile__form-button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('profile_img_input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            // imgタグのsrcを変更
            document.querySelector('.profile__img-circle img').src = e.target.result;
        }
        reader.readAsDataURL(file);
    });
    </script>

@endsection