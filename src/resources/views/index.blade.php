@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="index-form">
    @if (isset($keyword) && $keyword)
        <h2 class='index__title'>
            「{{ $keyword }}」の検索結果
        </h2>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif



    <div class="index-form__tabs">
        <h4 class="index__tab index__tab--active" data-target="recommend">おすすめ</h4>
        <h4 class="index__tab" data-target="mylist">マイリスト</h4>
    </div>

    {{-- おすすめ --}}
    <div class="index__items">
        <div id="recommend" class="tab-content active">
            @if ($recommendedItems->isEmpty())
                <p class="index__no-result">
                    @if (isset($keyword) && $keyword)
                    「{{ $keyword }}」を含むおすすめ商品はありませんでした。
                    @else
                        おすすめの商品はありません。
                    @endif
                </p>
            @else
                @foreach ($recommendedItems as $item)
                    <div class="index__item">
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="index__item-link">
                            <div class="item-img_wrapper" style='position: relative'>
                                <img src="{{ asset('storage/item_images/' . $item->item_img_url) }}" alt="{{ $item->name }}" class="index__item-img">
                                @if($item->soldItem)
                                    <span class="sold-label">SOLD</span>
                                @endif
                            </div>
                            <p class="index__item-name">{{ $item->name }}</p>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
        {{-- マイリスト --}}
        <div id="mylist" class="tab-content">
            @if ($mylistItems->isEmpty())
                <p class="index__no-result">
                    @if (isset($keyword) && $keyword)
                        「{{ $keyword }}」を含むマイリストの商品はありませんでした。
                    @else
                        マイリストに商品はありません。
                    @endif
                </p>
            @else
                @foreach ($mylistItems as $item)
                    <div class="index__item">
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="index__item-link">
                            <div class="item-img_wrapper" style="position: relative;">
                                <img src="{{ asset('storage/item_images/' . $item->item_img_url) }}" alt="{{ $item->name }}" class="index__item-img">
                                @if($item->soldItem)
                                    <span class="sold-label" >
                                        SOLD
                                    </span>
                                @endif
                            </div>
                        </a>
                        <p class="index__item-name">{{ $item->name }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- タブ切り替えJS --}}
<script>
    document.querySelectorAll('.index__tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // タブの active 切り替え
            document.querySelectorAll('.index__tab').forEach(t => t.classList.remove('index__tab--active'));
            this.classList.add('index__tab--active');

            // コンテンツ切り替え
            const target = this.dataset.target;
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(target).classList.add('active');
        });
    });
</script>
@endsection
