{{ $soldItem->item->user->name }} さん

商品「{{ $soldItem->item->name }}」の取引が完了しました。

購入者が取引を完了しました。
取引画面を開いて内容をご確認ください。

{{ route('transactions.show', $soldItem->id) }}