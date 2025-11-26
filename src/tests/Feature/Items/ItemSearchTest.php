<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_name_search_returns_partial_matches()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // 条件データ作成
        $condition = Condition::create(['condition' => '新品']);

        // 商品作成
        $item1 = Item::create([
            'name' => '青いシャツ',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明1',
            'item_img_url' => 'https://example.com/1.jpg',
        ]);

        $item2 = Item::create([
            'name' => '赤いシャツ',
            'price' => 1500,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明2',
            'item_img_url' => 'https://example.com/2.jpg',
        ]);

        $item3 = Item::create([
            'name' => '青いズボン',
            'price' => 2000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明3',
            'item_img_url' => 'https://example.com/3.jpg',
        ]);
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        // 検索実行（部分一致 "青"）
        $response = $this->get('/?keyword=青');

        $response->assertSee('青いシャツ');
        $response->assertSee('青いズボン');
        $response->assertDontSee('赤いシャツ');
    }

    public function test_search_keyword_is_preserved_in_mylist_page()
    {
        // 同じ準備をする
        $user = User::factory()->create();
        $condition = Condition::create(['condition' => '新品']);
        $item = Item::create([
            'name' => '青いシャツ',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'description' => '説明1',
            'item_img_url' => 'https://example.com/1.jpg',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        /** @var \App\Models\User $user */
        $this->actingAs($user)->get('/?keyword=青');

        // マイリストページに遷移
        $response = $this->actingAs($user)->get('/'); // まず / にアクセス
        $mylistItemsHtml = $response->viewData('mylistItems')->pluck('name')->toArray();

        // 期待商品があるか確認
        $this->assertContains('青いシャツ', $mylistItemsHtml);

        $response->assertSee('青いシャツ');
    }
}