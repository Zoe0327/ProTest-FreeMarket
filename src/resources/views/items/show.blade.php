@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css')}}">
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="show-items__container">
    <div class="show-items">
        <div class="show-items__img">
            <img src="{{ asset('storage/item_images/' . $item->item_img_url) }}" alt="商品画像">
        </div>

        <div class="show-items__content">
            <div class="item__header">
                <h2 class="item__name">{{ $item->name }}</h2>
                <p class="brand__name">{{ $item->brand_name }}</p>
                <p class="price">￥{{ number_format($item->price) }}<span>（税込）</span></p>

                <div class="item-actions">
                    {{-- ✅ button → aタグに変更 --}}
                    <a href="#" class="action-item like-button" data-item-id="{{ $item->id }}" data-like-url="{{ route('items.like', ['item' => $item->id]) }}">
                        @php
                            $liked = Auth::check() ? Auth::user()->likes->contains('item_id', $item->id) : false;
                        @endphp
                        <span class="icon" id="like-icon" style="color: {{ $liked ? '#E60023' : '#555' }}">★</span>
                        <span id="likes-count">{{ $item->likes->count() ?? 0 }}</span>
                    </a>

                    <span class="action-item">
                        <span class="icon">💬</span>
                        <span id="comments-count">{{ $item->comments->count() }}</span>
                    </span>
                </div>

                <div class="item-action-wrapper">
                    @if($item->soldItem)
                        <button class="buy-button sold" disabled>売り切れ</button>
                    @elseif(Auth::check() && Auth::id() === $item->user_id)
                        <p class="own-item-message">※自分が出品した商品です</p>
                    @else
                        <a href="{{ route('purchase.create', ['item_id' => $item->id]) }}" class="buy-button">購入手続きへ</a>
                    @endif
                </div>
            </div>

            <!-- 商品説明 -->
            <div class="item-section item-description">
                <h3>商品説明</h3>
                <p>{{ $item->description }}</p>
            </div>

            <!-- 商品の情報 -->
            <div class="item-section item-details">
                <h3>商品の情報</h3>
                <div class="info-group">
                    <p class="info-label">カテゴリー</p>
                    <div class="info-tags">
                        @foreach ($item->categories as $category)
                        <span class="tag">{{ $category->category_name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-group">
                    <p class="info-label">商品の状態</p>
                    <p class="info-value">{{ $item->condition->condition ?? '不明' }}</p>
                </div>
            </div>

            <!-- コメントセクション -->
            <div class="item-section comments-section">
                <h3>コメント({{ $item->comments->count() }})</h3>
                <div class="comment-list">
                    @forelse ($item->comments as $comment)
                        <div class="comment">
                            <div class="comment-body">
                                <img src="{{ asset('storage/' . (optional($comment->user->profile)->profile_img_url ?? 'default-avatar.png')) }}" alt="ユーザーアバター" class="comment-avatar">
                                <p class="comment-user">{{ optional($comment->user)->name ?? '匿名' }}</p>
                            </div>
                            <p class="comment-text">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p>コメントはまだありません。</p>
                    @endforelse
                </div>

                @if(Auth::id() !== $item->user_id)
                    <div class="comment-form">
                        <h4>商品へのコメント</h4>
                        <form class="comment-form" action="{{ route('comments.store', $item->id) }}" method="POST">
                            @csrf
                            <textarea class="comment-text" name="comment" class="comment-textarea" placeholder="コメントを入力してください"></textarea>
                            <div class="comment-error">
                                @error('comment')
                                    {{ $message }}
                                @enderror
                            </div>
                            <button type="submit" class="comment-submit-button">コメントを送信する</button>
                        </form>
                    </div>
                @elseif(Auth::check() && Auth::id() === $item->user_id)
                    <p class="own-item-message">※自分の出品にはコメントはできません</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const getCsrfToken = () => {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute('content') : '';
};

document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

    // =============================
    // いいね機能 (aタグクリック)
    // =============================
    document.body.addEventListener('click', async function(e) {
        const link = e.target.closest('.like-button');
        if (!link) return; // .like-button以外は無視
        e.preventDefault();

        if (!isLoggedIn) {
            alert('いいねをするにはログインが必要です。');
            window.location.href = '{{ route('login') }}';
            return;
        }

        const url = link.dataset.likeUrl;
        const icon = link.querySelector('#like-icon');
        const countEl = link.querySelector('#likes-count');

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                },
            });

            if (!res.ok) {
                console.error('Like request failed', res.status);
                return;
            }

            const data = await res.json();

            if (countEl) countEl.textContent = data.likes_count;
            if (icon) icon.style.color = data.liked ? '#E60023' : '#555';
        } catch (err) {
            console.error('Like fetch error', err);
        }
    });
});
</script>
@endsection
