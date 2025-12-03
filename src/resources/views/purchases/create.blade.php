@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/create.css')}}">
@endsection

@section('content')

<div class="purchases__form">
    <form method="POST" action="{{ route('purchases.store', ['item_id' => $item->id]) }}" class="purchase-form">
        @csrf
        <div class="purchase-content-wrapper">
            <div class="purchase__item">
                <div class="purchase-item__img">
                    <img src="{{ asset('storage/item_images/' .$item->item_img_url) ?? 'https://placehold.co/120x120/eee/333?text=商品画像' }}" alt="商品画像" class="item-summary__image">
                    <div class="purchase-item__detail">
                        <h2 class="purchase-item__name">{{ $item->name ?? '商品名' }}</h2>
                        <p class="purchase-price">￥{{ number_format($item->price) }}</p>
                    </div>
                </div>
                <!-- 区切り線用のダミーdiv (CSSでボーダーを表現) -->
                <div class="separator"></div>

                <div class="purchase-item__payment">
                    <div class="payment__header">
                        <p class="purchase-payment">支払方法</p>
                    </div>
                    <select name="payment_method" id="payment_method_id" class="payment-select">
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード払い" selected>カード払い</option>
                    </select>
                </div>
                <!-- 区切り線用のダミーdiv (CSSでボーダーを表現) -->
                <div class="separator"></div>

                <div class="purchase-item__address">
                    <div class="address__header">
                        <p class="delivery-address">配送先</p>

                        <a href="{{ route('purchases.address.edit', $item->id) }}" class="address-edit">変更する</a>
                    </div>
                    <div class="confirmed-address">
                        <p class="address-zip">〒{{ $deliveryAddress['post_code'] }}</p>
                        <p class="address-full">
                            {{ $deliveryAddress['address'] }}
                            @if(!empty($deliveryAddress['building']))
                                {{ $deliveryAddress['building'] }}
                            @endif
                        </p>
                    </div>
                </div>
                <!-- 区切り線用のダミーdiv (CSSでボーダーを表現) -->
                <div class="separator"></div>
            </div>
            <div class="purchase__item-confirmation">
                <div class="confirmation-form">
                    <!-- 商品代金 (ご提示いただいたtr/tdの代わりに、CSSでレイアウトしやすいdivを使用) -->
                    <div class="confirmation-row">
                        <span class="row-label">商品代金</span>
                        <span class="row-value">￥{{ number_format($item->price) }}</span>
                    </div>
                    <div class="confirmation-row">
                        <span class="row-label">支払い方法</span>
                        <span class="row-value" id="displayed_payment_method">カード払い</span>
                    </div>
                    <div class="confirmation-row confirmation-row--total">
                        <span class="row-label">支払い金額</span>
                        <span class="row-value total-price">￥{{ number_format($item->price) }}</span>
                        <span class="row-value" id="total_payment_method">カード払い</span>
                    </div>
                </div>
                <div class="confirm-button">
                    <button type="submit" id="stripe-button" class="btn-purchase-submit">購入する</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    /**
     * 支払い方法のセレクトボックスが変更された際に、確認エリアの表示を更新する
     */
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('payment_method_id');
        const stripeButton = document.getElementById('stripe-button');

        // Stripe初期化
        const stripe = Stripe('{{ env('STRIPE_KEY') }}'); // .envの公開キーを使う

        // ページロード時にも一度実行して初期値を設定
        updatePaymentMethod();selectElement.addEventListener('change', updatePaymentMethod);

// Stripe支払いボタンクリック時
stripeButton.addEventListener('click', function(e) {
    e.preventDefault();
    const selectedMethod = selectElement.value;

    if (selectedMethod === 'カード払い') {
        // Stripeのセッションをサーバー側で作成
        fetch("{{ route('checkout.session') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ item_id: {{ $item->id }} })
        })
        .then(response => response.json())
        .then(session => {
            // Stripeの決済ページにリダイレクト
            return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .then(result => {
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(error => console.error("Error:", error));
    } else {
        // コンビニ払いの場合は通常のフォーム送信
        document.querySelector('.purchase-form').submit();
    }
});
});

function updatePaymentMethod() {
const selectElement = document.getElementById('payment_method_id');
const displayedPaymentMethod = document.getElementById('displayed_payment_method');
const totalPaymentMethod = document.getElementById('total_payment_method');

if (selectElement && displayedPaymentMethod) {
    const methodText = selectElement.options[selectElement.selectedIndex].text;
    displayedPaymentMethod.textContent = methodText;
    if (totalPaymentMethod) {
        totalPaymentMethod.textContent = methodText;
    }
}
}
</script>
@endsection