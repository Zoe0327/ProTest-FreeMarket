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
                    <img id="image-preview" src="https://placehold.co/200x200/eee/333?text=画像を選択" alt="商品画像プレビュー" class="item-preview-img">
                    <label for="image-upload" class="custom-file-upload-btn">
                        画像を選択する
                    </label>
                    <input type="file" id="image-upload" name="image" accept="image/*" class="hidden-input">
                </div>
            </div>
            @error('image')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>

        <hr class="form-divider">

        <div class="form-section">
            <h3 class="section-title">商品の詳細</h3>
            <div class="sell__separator"></div>

            <div class="sell-form-group">
                <p class="sell-form-label">カテゴリー</p>
                <div class="category-chips-wrapper">
                    <!-- DBからの取得を想定し、タグ風のスタイルを適用 -->
                    <span class="category-chip">ファッション</span>
                    <span class="category-chip">家電</span>
                    <span class="category-chip">インテリア</span>
                    <span class="category-chip">レディース</span>
                    <span class="category-chip">メンズ</span>
                    <span class="category-chip">コスメ</span>
                    <span class="category-chip">本</span>
                    <span class="category-chip">ゲーム</span>
                    <span class="category-chip">スポーツ</span>
                    <span class="category-chip">キッチン</span>
                    <span class="category-chip">ハンドメイド</span>
                    <span class="category-chip">アクセサリー</span>
                    <span class="category-chip">おもちゃ</span>
                    <span class="category-chip">ベビー・キッズ</span>
                    <!-- 実際には選択可能な要素 (例: hidden input や select) に置き換える必要があります -->
                </div>
                @error('category_id')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="sell-form-group">
                <p class="sell-form-label">商品の状態</p>
                <div class="select-wrapper">
                    <select name="condition_id" class="sell-form-select">
                        <option value="">選択してください</option>
                        <option value="1">良好</option>
                        <option value="2">目立った傷や汚れなし</option>
                        <option value="3">やや傷や汚れあり</option>
                        <option value="4">状態が悪い</option>
                    </select>
                </div>
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
                    <input type="text" name="brand" value="{{ old('brand') }}" class="sell-form-input" placeholder="" />
                </div>

                <div class="sell-form-group">
                    <p class="sell-form-label">商品の説明</p>
                    <textarea name="detail" class="sell-form-textarea" placeholder="">{{ old('detail') }}</textarea>
                    @error('detail')
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

@endsection