@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="index-form">
    <div class="index-form__tabs">
        <h4 class="index__tab index__tab--active" data-target="recommend">おすすめ</h4>
        <h4 class="index__tab" data-target="mylist">マイリスト</h4>
    </div>

    {{-- おすすめ --}}
    <div class="index__items">
        <div id="recommend" class="tab-content active">
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
        </div>
        {{-- マイリスト --}}
        <div id="mylist" class="tab-content">
            @foreach ($mylistItems as $item)
                <div class="index__item">
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="index__item-link">
                        <div class="item-img-wrapper" style="position: relative;">
                            <img src="{{ asset('storage/item_images/' . $item->item_img_url) }}" alt="{{ $item->name }}" class="index__item-img">
                            @if($item->soldItem)
                                <span class="sold-label" style="position: absolute; top: 5px; left: 5px; background: red; color: white; padding: 2px 6px; font-size: 12px; border-radius: 4px;">
                                    SOLD
                                </span>
                            @endif
                        </div>
                    </a>
                    <p class="index__item-name">{{ $item->name }}</p>
                </div>
            @endforeach
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
