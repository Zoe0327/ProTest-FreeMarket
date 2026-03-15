<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\SoldItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLikeListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ① いいねした商品だけがマイリストに表示される
     */
    public function test_user_can_see_only_liked_items()
    {
        // ユーザー作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);

        // 条件データを先に作成
        $condition = \App\Models\Condition::create(['condition' => '新品']);

        // ユーザー作成
        $user = \App\Models\User::create([
            'name' => 'テストユーザー',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);

        /** @var \App\Models\User $user */
        // 商品作成（必須カラムを全て指定）
        $itemA = \App\Models\Item::create([
            'name' => '商品A',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => 'テスト用商品Aの説明',
            'item_img_url' => 'https://example.com/imageA.jpg',
        ]);

        $itemB = \App\Models\Item::create([
            'name' => '商品B',
            'price' => 2000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => 'テスト用商品Bの説明',
            'item_img_url' => 'https://example.com/imageB.jpg',
        ]);

        $itemC = \App\Models\Item::create([
            'name' => '商品C',
            'price' => 3000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => 'テスト用商品Cの説明',
            'item_img_url' => 'https://example.com/imageC.jpg',
        ]);

        // いいね作成
        Like::create(['user_id' => $user->id, 'item_id' => $itemA->id]);
        Like::create(['user_id' => $user->id, 'item_id' => $itemB->id]);

        // ページにアクセス
        $response = $this->actingAs($user)->get('/');

        $response->assertSee('商品A');
        $response->assertSee('商品B');
        $response->assertDontSee('商品C');
    }

    /**
     * ② 購入済み商品は SOLD と表示される
     */
    public function test_sold_item_shows_sold_label_in_mylist()
    {
        $condition = \App\Models\Condition::create(['condition' => '新品']);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'user3@example.com',
            'password' => bcrypt('password'),
        ]);

        $item = Item::create(['name' => '売れた商品', 'price' => 5000, 'user_id' => $user->id, 'condition_id' => $condition->id, 'description' => 'テスト用売れた商品', 'item_img_url' => 'https://example.com/sold.jpg',]);

        /** @var \App\Models\User $user */
        // いいねした状態にする
        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        // 売れた商品として登録
        SoldItem::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '東京都千代田区1-1-1',
            'sending_name' => 'テスト 太郎',
            'payment_method' => 'クレジットカード',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('SOLD');
    }

    /**
     * ③ 未ログイン（ゲスト）はマイリストに何も表示されない
     */
    public function test_guest_sees_no_mylist_items()
    {
        $response = $this->get('/');

        $response->assertSee('マイリストに商品はありません。');
    }
}
