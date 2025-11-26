<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_page_displays_all_information()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // 条件データ作成
        $condition = Condition::create(['condition' => '新品']);

        // カテゴリ作成
        $category = Category::create(['category_name' => 'ファッション']);

        // 商品作成
        $item = Item::create([
            'name' => '青いシャツ',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => 'テスト商品説明',
            'item_img_url' => 'test.jpg',
            'brand_name' => 'テストブランド',
        ]);

        // 商品とカテゴリを紐付け（attachで対応）
        $item->categories()->attach($category->id);

        // いいね
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // コメント
        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        /** @var \App\Models\User $user */
        // 商品詳細ページにアクセス
        $response = $this->actingAs($user)->get(route('items.show', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($item->brand_name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($category->category_name);
        $response->assertSee($item->condition->condition);
        $response->assertSee('テスト商品説明');
        $response->assertSee('テストコメント');
        $response->assertSee($user->name);
    }

    public function test_item_detail_page_displays_multiple_categories()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // 条件作成
        $condition = Condition::create(['condition' => '新品']);

        // 複数カテゴリ作成
        $category1 = Category::create(['category_name' => 'ファッション']);
        $category2 = Category::create(['category_name' => 'メンズ']);

        // 商品作成
        $item = Item::create([
            'name' => '赤いシャツ',
            'price' => 1500,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => 'テスト商品説明',
            'item_img_url' => 'test2.jpg',
        ]);

        // 商品とカテゴリを紐付け（attachで対応）
        $item->categories()->attach([$category1->id, $category2->id]);

        /** @var \App\Models\User $user */
        // 商品詳細ページにアクセス
        $response = $this->actingAs($user)->get(route('items.show', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee($category1->category_name);
        $response->assertSee($category2->category_name);
    }
}
