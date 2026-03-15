<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;
use App\Models\Category;
use App\Models\CategoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemLikeTest extends TestCase
{
    use RefreshDatabase;

    /** ① いいねできる */
    public function test_user_can_like_an_item()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => 'ファッション']);

        $item = Item::create([
            'name' => 'テスト商品',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明文',
            'item_img_url' => 'test.jpg',
        ]);

        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        // いいね POST
        $this->actingAs($user)->post(route('items.like', ['item' => $item->id]));

        // DB に登録されていること
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** ② いいね済みのアイコンが赤色で表示される */
    public function test_liked_item_shows_red_icon()
    {
        $user = User::create([
            'name' => 'test2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '雑貨']);

        $item = Item::create([
            'name' => 'テスト商品2',
            'price' => 2000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明文2',
            'item_img_url' => 'test2.jpg',
        ]);

        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        // 先にいいねを付ける
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品詳細ページを表示
        $response = $this->actingAs($user)->get(route('items.show', ['item_id' => $item->id]));

        // 重要：赤色（#E60023）が表示されていること
        $response->assertSee('#E60023');
    }

    /** ③ いいね解除できる */
    public function test_user_can_unlike_an_item()
    {
        $user = User::create([
            'name' => 'test3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => '家電']);

        $item = Item::create([
            'name' => 'テスト商品3',
            'price' => 3000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明文3',
            'item_img_url' => 'test3.jpg',
        ]);

        $categoryItem = new CategoryItem();
        $categoryItem->item_id = $item->id;
        $categoryItem->category_id = $category->id;
        $categoryItem->save();

        // 先にいいね状態
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // もう一度押して解除する
        $this->actingAs($user)->post(route('items.like', ['item' => $item->id]));

        // likes テーブルから削除されているか？
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
