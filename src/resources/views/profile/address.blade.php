@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/address.css')}}">
@endsection


@section('content')
<!-- 成功メッセージの表示 -->
@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="profile__address-container">
    <div class="profile__address-title">
        <h1>住所の変更</h1>
    </div>
    <form method="POST" action="{{ route('profile.address.update') }}" class="profile__address-content" enctype="multipart/form-data">
        @csrf
        <div class="profile__address-each">
            <p>郵便番号</p>
            <input type="text" name="post_code" value="">
            @error('post_code')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="profile__address-each">
            <p>住所</p>
            <input type="text" name="address" value="">
            @error('address')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="profile__address-each">
            <p>建物名</p>
            <input type="text" name="building" value="">
            @error('building')<div class="error-message">{{ $message }}</div>@enderror
        </div>


        <div class="profile__address-button">
            <button class="address__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

@endsection