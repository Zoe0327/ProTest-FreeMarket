@extends('layouts.auth')
@section('css')
<link rel="stylesheet" href="{{ asset('css/transactions/show.css')}}">
@endsection

@section('content')
<div class="transaction-container">
    <aside class="transaction-sidebar">
        <h2>その他の取引</h2>

        @foreach($transactions as $transaction)
            <a href="{{ route('transactions.show', $transaction->id) }}" class="transaction-sidebar__item">
                <div class="transaction-sidebar__content" title="{{ $transaction->item->name }}">
                    {{ $transaction->item->name }}
                </div>
            </a>
        @endforeach
    </aside>

    <main class="transaction-main">
        <div class="transaction-header">
            <div class="transaction-header-user">
                @if($partner->profile && $partner->profile->profile_img_url)
                    <img src="{{ asset('storage/' . $partner->profile->profile_img_url) }}" alt="ユーザー画像">
                @else
                    <img src="{{ asset('storage/item_images/default-user.png') }}" alt="ユーザー画像">
                @endif

                <h1>「{{ $partner->name }}」さんとの取引画面</h1>
            </div>

            @if(Auth::id() === $soldItem->user_id && $soldItem->status === 'in_progress')
                <form action="{{ route('transactions.complete', $soldItem->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="transaction-complete-button">
                        取引を完了する
                    </button>
                </form>
            @endif
        </div>

        <div class="transaction-item">
            <img src="{{ asset('storage/item_images/' . $soldItem->item->item_img_url) }}" alt="商品画像">
            <div class="transaction-item-info">
                <h2>{{ $soldItem->item->name }}</h2>
                <p>￥{{ number_format($soldItem->item->price) }}</p>
            </div>
        </div>

        <div class="transaction-chat">
            @foreach($soldItem->messages as $message)
                @if(Auth::id() === $message->user_id)
                    <div class="chat-message chat-message--right">
                        <div class="chat-message-user chat-message-user--right">
                            <p>{{ $message->user->name }}</p>
                            @if($message->user->profile && $message->user->profile->profile_img_url)
                                <img src="{{ asset('storage/' . $message->user->profile->profile_img_url) }}" alt="ユーザー画像">
                            @else
                                <img src="{{ asset('storage/item_images/default-user.png') }}" alt="ユーザー画像">
                            @endif
                        </div>

                        <div class="chat-message-body chat-message-body--right">
                            @if(!$isCompleted && request('edit_message') == $message->id)
                                <form action="{{ route('messages.update', $message->id) }}" method="POST" class="chat-message-edit-form">
                                    @csrf
                                    @method('PATCH')

                                    <textarea name="message" class="chat-message-edit-textarea">{{ old('message', $message->message) }}</textarea>

                                    <div class="chat-message-edit-actions">
                                        <button type="submit" class="chat-message-update-button">更新</button>
                                        <a href="{{ route('transactions.show', $soldItem->id) }}" class="chat-message-cancel-link">キャンセル</a>
                                    </div>
                                </form>
                            @else
                                <p>{{ $message->message }}</p>
                            @endif

                            @if($message->message_img_url)
                                <img src="{{ asset('storage/message_images/' . $message->message_img_url) }}" alt="メッセージ画像" class="chat-message-image">
                            @endif
                        </div>

                        @if(!$isCompleted)
                            <div class="chat-message-actions">
                                <a href="{{ route('transactions.show', ['soldItem' => $soldItem->id, 'edit_message' => $message->id]) }}" class="chat-message-action-link">
                                    編集
                                </a>
                                <form action="{{ route('messages.destroy', $message->id) }}" method="POST" style="display:inline;" class="chat-message-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="chat-message-action-button" onclick="return confirm('削除しますか？')">削除</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="chat-message chat-message--left">
                        <div class="chat-message-user chat-message-user--left">
                            @if($message->user->profile && $message->user->profile->profile_img_url)
                                <img src="{{ asset('storage/' . $message->user->profile->profile_img_url) }}" alt="ユーザー画像">
                            @else
                                <img src="{{ asset('storage/item_images/default-user.png') }}" alt="ユーザー画像">
                            @endif
                            <p>{{ $message->user->name }}</p>
                        </div>

                        <div class="chat-message-body chat-message-body--left">
                            <p>{{ $message->message }}</p>

                            @if($message->message_img_url)
                                <img src="{{ asset('storage/message_images/' . $message->message_img_url) }}" alt="メッセージ画像" class="chat-message-image">
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if(!$isCompleted)
            <div class="transaction-chat-form">
                @if ($errors->any())
                    <div class="chat-form-error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" class="chat-form">
                    @csrf
                    <input type="hidden" name="sold_item_id" value="{{ $soldItem->id }}">

                    <textarea id="chat-message" name="message" class="chat-form-textarea" placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>

                    <div class="chat-form-actions">
                        <label for="message_img" class="chat-form-image-label">画像を追加</label>
                        <input type="file" name="message_img_url" id="message_img" class="chat-form-file">

                        <button type="submit" class="chat-form-submit">
                            <img src="{{ asset('images/紙飛行機マーク.jpg') }}" alt="送信">
                        </button>
                    </div>
                </form>
            </div>
        @else
            <p class="chat-completed-message">
                この取引は完了しているため、メッセージの送信はできません。
            </p>
        @endif
    </main>
</div>

<div id="review-modal" class="review-modal hidden">
    <div class="review-modal__content">
        <div class="review-modal__header">
            <h2>取引が完了しました。</h2>
        </div>

        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="sold_item_id" value="{{ $soldItem->id }}">
            <input type="hidden" name="reviewee_id" value="{{ $partner->id }}">
            <input type="hidden" name="rating" id="rating-value">

            <div class="review-modal__body">
                <p class="review-modal__text">今回の取引相手はどうでしたか？</p>

                <div class="review-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="review-star" data-value="{{ $i }}">★</button>
                    @endfor
                </div>
            </div>

            <div class="review-modal__footer">
                <button type="submit" class="review-modal__submit">送信する</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('review-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('review-modal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.review-star');
    const ratingInput = document.getElementById('rating-value');

    stars.forEach(star => {
        star.classList.add('review-star--inactive');

        star.addEventListener('click', function () {
            const value = Number(this.dataset.value);

            ratingInput.value = value;

            stars.forEach(s => {
                s.classList.add('review-star--inactive');
            });

            stars.forEach(s => {
                if (Number(s.dataset.value) <= value) {
                    s.classList.remove('review-star--inactive');
                }
            });
        });
    });

    @if($shouldShowModal)
        openModal();
    @endif

    const textarea = document.getElementById('chat-message');

    if (textarea) {
        // 保存データ復元
        const savedMessage = localStorage.getItem('chat_message');
        if (savedMessage) {
            textarea.value = savedMessage;
        }

        // 入力時保存
        textarea.addEventListener('input', function () {
            localStorage.setItem('chat_message', textarea.value);
        });

        // 送信時削除
        const form = textarea.closest('form');
        form.addEventListener('submit', function () {
            localStorage.removeItem('chat_message');
        });
    }
});
</script>
@endsection