@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/address.css')}}">
@endsection


@section('content')
<!-- 成功メッセージの表示 -->
@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/address.css')}}">
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
    <form method="POST" action="{{ route('purchases.address.update', ['item_id' => $item->id]) }}" class="profile__address-content">
        @csrf
        <div class="profile__address-each">
            <p>郵便番号</p>
            <input type="text" name="post_code" value="{{ old('post_code', $profile->post_code ?? '') }}">
            @error('post_code')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="profile__address-each">
            <p>住所</p>
            <input type="text" name="address" value="{{ old('address',  $profile->address ?? '') }}">
            @error('address')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="profile__address-each">
            <p>建物名</p>
            <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}">
            @error('building')<div class="error-message">{{ $message }}</div>@enderror
        </div>


        <div class="profile__address-button">
            <button class="address__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

@endsection
<div class="profile__address-container">
    <div class="profile__address-title">
        <h1>住所の変更</h1>
    </div>
    <form method="POST" action="{{ route('purchases.address.update', ['item_id' => $item->id]) }}" class="profile__address-content">
        @csrf
        <div class="profile__address-each">
            <p>郵便番号</p>
            <input type="text" name="post_code" value="{{ old('post_code', $profile->post_code ?? '') }}">
            @error('post_code')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="profile__address-each">
            <p>住所</p>
            <input type="text" name="address" value="{{ old('address',  $profile->address ?? '') }}">
            @error('address')<div class="error-message">{{ $message }}</div>@enderror
        </div>

        <div class="profile__address-each">
            <p>建物名</p>
            <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}">
            @error('building')<div class="error-message">{{ $message }}</div>@enderror
        </div>


        <div class="profile__address-button">
            <button class="address__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>

@endsection