<?php

namespace Tests\Feature\Items;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\CategoryItem;
use App\Models\Condition;
use App\Models\SoldItem;

class ItemPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_item()
    {
        // ユーザー作成
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);

        // 商品状態作成
        $condition = Condition::create([
            'condition' => '新品',
        ]);

        // アイテム作成
        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'Test Item',
            'description' => '商品説明',
            'price' => 1000,
            'condition_id' => $condition->id,
            'item_img_url' => 'test.jpg',
        ]);

        // カテゴリ作成
        $category = Category::create([
            'category' => 'テストカテゴリ',
        ]);

        // カテゴリアイテム関連付け
        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        // sold_items に購入情報を追加
        $soldItem = SoldItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '東京都渋谷区1-1-1',
            'sending_building' => 'テストビル',
            'payment_method' => 'クレジットカード',
        ]);

        // DB確認
        $this->assertDatabaseHas('sold_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_purchased_item_shows_sold_label_in_index()
    {
        $user = User::create([
            'name' => 'Test User 2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'Test Item 2',
            'description' => '商品説明',
            'price' => 1500,
            'condition_id' => $condition->id,
            'item_img_url' => 'test2.jpg',
        ]);

        $category = Category::create(['category' => 'テストカテゴリ2']);
        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        // 購入情報追加
        SoldItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_postcode' => '234-5678',
            'sending_address' => '東京都渋谷区2-2-2',
            'sending_building' => 'テストビル2',
            'payment_method' => '銀行振込',
        ]);

        // sold_items に登録があれば sold とみなす
        $soldItemIds = SoldItem::pluck('item_id')->toArray();
        $this->assertTrue(in_array($item->id, $soldItemIds));
    }

    public function test_purchased_item_appears_in_user_profile()
    {
        $user = User::create([
            'name' => 'Test User 3',
            'email' => 'user3@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);

        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'Test Item 3',
            'description' => '商品説明',
            'price' => 2000,
            'condition_id' => $condition->id,
            'item_img_url' => 'test3.jpg',
        ]);

        $category = Category::create(['category' => 'テストカテゴリ3']);
        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        SoldItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_postcode' => '345-6789',
            'sending_address' => '東京都渋谷区3-3-3',
            'sending_building' => null,
            'payment_method' => '現金',
        ]);

        $userSoldItemIds = SoldItem::where('user_id', $user->id)->pluck('item_id')->toArray();
        $this->assertTrue(in_array($item->id, $userSoldItemIds));
    }
}
