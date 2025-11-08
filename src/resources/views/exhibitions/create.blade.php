@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibitions/create.css')}}">
@endsection


@section('content')
<div class="items__sell-form">
    <div class="sell-form__header">
        <h2>商品の出品</h2>
    </div>
    <form method="POST" action="{{ route('sell.store') }}" class="sell-form" enctype="multipart/form-data">
        @csrf
        <div class="form-section">
            <h3 class="section__img-title">商品画像</h3>
            <div class="image-upload-wrapper">
                <div class="image-upload-area">
                    <img id="image-preview" class="item-preview-img hidden" alt="商品画像プレビュー">
                    <label for="image-upload" class="custom-file-upload-btn">
                        画像を選択する
                    </label>
                    <input type="file" id="image-upload" name="item_img_url" accept="image/*" class="hidden-input">
                </div>
            </div>
            @error('item_img_url')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-section">
            <h3 class="section-title">商品の詳細</h3>
            <div class="sell__separator"></div>

            <div class="sell-form-group">
                <p class="sell-form-label">カテゴリー</p>
                <div class="category-chips-wrapper">
                    @foreach ($categories as $category)
                        <span class="category-chip" data-id="{{ $category->id }}" onclick="toggleCategory(this)">
                            {{ $category->category_name }}
                        </span>
                    @endforeach
                </div>
                @php
                $categoryOldValue = old('category_id');
                if (is_array($categoryOldValue)) {
                    $categoryOldValue = implode(',', $categoryOldValue);
                }
                @endphp
                <input type="hidden" name="category_id" id="selected-categories" value="{{ $categoryOldValue }}">
                @error('category_id')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sell-form-group">
                <p class="sell-form-label">商品の状態</p>
                <div class="select-wrapper">
                    <select name="condition_id" class="sell-form-select">
                        <option value="">選択してください</option>
                        @foreach ($conditions as $condition)
                        <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
                        @endforeach
                    </select>
                </div>
                @error('condition_id')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-section">
                <h3 class="section-title">商品名と説明</h3>
                <div class="sell__separator"></div>

                <div class="sell-form-group">
                    <p class="sell-form-label">商品名</p>
                    <input type="text" name="name" value="{{ old('name') }}" class="sell-form-input" placeholder="" />
                    @error('name')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <p class="sell-form-label">ブランド名</p>
                    <input type="text" name="brand_name" value="{{ old('brand_name') }}" class="sell-form-input" placeholder="" />
                </div>

                <div class="sell-form-group">
                    <p class="sell-form-label">商品の説明</p>
                    <textarea name="description" class="sell-form-textarea">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <p class="sell-form-label">販売価格</p>
                    <div class="price-input-wrapper">
                        <span class="yen-mark">￥</span>
                        <input type="number" name="price" value="{{ old('price') }}" class="sell-form-input" min="0" max="10000" />
                    </div>
                    @error('price')
                        <p class="form__error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="sell__item-button">
            <button class="sell__button-submit" type="submit">出品する</button>
        </div>
    </form>
</div>
<script>
    function toggleCategory(el) {
        const hiddenInput = document.getElementById('selected-categories');
        let selected = hiddenInput.value ? hiddenInput.value.split(',').filter(id => id !== "") : [];

        const clickedId = el.dataset.id;

        if (selected.includes(clickedId)) {
            // 選択解除
            selected = selected.filter(id => id !== clickedId);
            el.classList.remove('selected');
        } else {
            // 選択追加
            selected.push(clickedId);
            el.classList.add('selected');
        }

        hiddenInput.value = selected.join(',');
    }
    document.getElementById('image-upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result; // 画像をプレビューに表示
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file); // ファイルを読み込む
        }
    });
    </script>

    <style>
    .category-chip {
        display: inline-block;
        padding: 5px 12px;
        margin: 4px;
        border: 1px solid #FF5555;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .category-chip.selected {
        background-color: #FF5555;
        color: #fff;
        border-color: #FF5555;
    }
    .hidden {
        display: none;
    }
    </style>
@endsection