@extends(Auth::check() ? 'layouts.auth' : 'layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="index-form">
    <div class="index-form__tabs">
        <h4 class="index__tab index__tab--active">おすすめ</h4>
        <h4 class="index__tab">マイリスト</h4>
    </div>
    
    <div class="index__items">
        @for ($i = 0; $i < 8; $i++)
            <div class="index__item">
                <img src="{{ asset('images/sample-item.png') }}" alt="商品画像" class="index__item-img">
                <p class="index__item-name">商品名 {{ $i + 1 }}</p>
            </div>
        @endfor
    </div>
</div>

@endsection