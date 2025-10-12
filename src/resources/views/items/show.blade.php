@extends(auth()->check() ? 'layouts.auth' : 'layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css')}}">
@endsection

@section('content')
<div class="show-items__container">
    <div class="show-items">
        <div class="show-items__img">
            <img src="" alt="商品画像">
        </div>
        <div class="show-items__content">
            <div class="item__header">
                <h2 class="item__name">商品名がここに入る</h2>
                <p class="brand__name">ブランド名</p>
                <p class="price">￥47,000<span>（税込）</span></p>
                <div class="item-actions">
                    <span class="action-item"><span class="icon">★</span> 3</span>
                    <span class="action-item"><span class="icon">💬</span> 1</span>
                </div>
                <div class="item-action-wrapper">
                    <a href="#" class="buy-button">購入手続きへ</a>
                </div>
            </div>
            <!-- 商品説明 -->
            <div class="item-section item-description">
                <h3>商品説明</h3>
                <p>カラー：グレー</p>
                <p>新品</p>
                <p>商品の状態は良好です。傷もありません。</p>
                <p>購入後、即発送いたします。</p>
            </div>
            <!-- 商品の情報 -->
            <div class="item-section item-details">
                <h3>商品の情報</h3>
                <div class="info-group">
                    <p class="info-label">カテゴリー</p>
                    <div class="info-tags">
                        <!-- 実際にはカテゴリーを動的に表示します -->
                        <span class="tag">洋服</span>
                        <span class="tag">メンズ</span>
                    </div>
                </div>
                <div class="info-group">
                    <p class="info-label">商品の状態</p>
                    <p class="info-value">良好</p>
                </div>
            </div>
            <!-- コメントセクション -->
            <div class="item-section comments-section">
                <h3>コメント(1)</h3>
                <!-- 既存のコメント -->
                <div class="comment-list">
                    <div class="comment">
                        <div class="comment-body">
                            <!-- 修正: <img> タグを使用してアバター画像を表示 -->
                            <img src="" alt="ユーザーアバター" class="comment-avatar">
                            <p class="comment-user">admin</p>
                        </div>
                        <p class="comment-text">こちらにコメントが入ります。</p>
                    </div>
                </div>
                <!-- コメント入力フォーム -->
                <div class="comment-form">
                    <h4>商品へのコメント</h4>
                    <!-- 実際にはフォームタグとアクションを設定します -->
                    <form action="/comments" method="POST">
                        <!-- @csrf -->
                        <textarea name="comment" class="comment-textarea" placeholder="コメントを入力してください"></textarea>
                        <button type="submit" class="comment-submit-button">コメントを送信する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
