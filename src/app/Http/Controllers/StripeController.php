<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;


class StripeController extends Controller
{
    public function session(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = Item::findOrFail($request->item_id);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => (int)$item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => Auth::id()
            ],
        ]);
        return response()->json(['id' => $session->id]);
    }
    public function success()
    {
        return redirect()->route('items.index')->with('success','決済が完了しました！');
    }

    public function cancel()
    {
        return view('stripe.cancel');
    }
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('❌ Invalid payload: ' . $e->getMessage());
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('❌ Invalid signature: ' . $e->getMessage());
            return response('', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $item_id = $session->metadata->item_id ?? null;
            $user_id = $session->metadata->user_id ?? null;

            if ($item_id && $user_id) {
                $item = \App\Models\Item::find($item_id);
                $user = \App\Models\User::find($user_id);

                if ($item && $user) {
                    // 🔹 すでに売却済みならスキップ（Stripeが再送しても重複登録しないように）
                    $exists = \App\Models\SoldItem::where('item_id', $item_id)->exists();
                    if (!$exists) {
                        \App\Models\SoldItem::create([
                            'user_id' => $user->id,
                            'item_id' => $item->id,
                            'sending_post_code' => $user->profile->post_code ?? '',
                            'sending_address' => $user->profile->address ?? '',
                            'sending_building' => $user->profile->building ?? '',
                            'payment_method' => 'カード払い',
                        ]);

                        // 🔹 itemテーブルのステータスを更新（is_soldなどがある場合）
                        if (isset($item->is_sold)) {
                            $item->update(['is_sold' => true]);
                        }

                        Log::info("✅ Sold item registered successfully.", [
                            'item_id' => $item_id,
                            'user_id' => $user_id
                        ]);
                    }
                }
            }
        }
        return response('Webhook handled', 200);
    }

}
