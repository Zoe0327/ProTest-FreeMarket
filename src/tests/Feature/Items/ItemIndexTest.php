<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\SoldItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function item_index_displays_correctly()
    {
        // 1. ユーザー作成
        $user1 = User::create([
            'name' => '出品者1',
            'email' => 'seller1@example.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::create([
            'name' => '出品者2',
            'email' => 'seller2@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. 条件作成
        $condition = Condition::create(['condition' => '新品']);

        // 3. 商品作成
        $item1 = Item::create([
            'user_id' => $user1->id,
            'condition_id' => $condition->id,
            'name' => '商品A',          // 自分の商品
            'price' => 1000,
            'brand_name' => 'ブランドA',
            'description' => '説明A',
            'item_img_url' => 'https://via.placeholder.com/150',
        ]);

        $item2 = Item::create([
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'name' => '商品B',          // 他人・未購入
            'price' => 2000,
            'brand_name' => 'ブランドB',
            'description' => '説明B',
            'item_img_url' => 'https://via.placeholder.com/150',
        ]);

        $item3 = Item::create([
            'user_id' => $user2->id,
            'condition_id' => $condition->id,
            'name' => '商品C',          // 他人・購入済み
            'price' => 3000,
            'brand_name' => 'ブランドC',
            'description' => '説明C',
            'item_img_url' => 'https://via.placeholder.com/150',
        ]);

        // 4. SOLD商品作成
        SoldItem::create([
            'item_id' => $item3->id,
            'user_id' => $user1->id,
            'sending_postcode' => '123-4567',
            'sending_prefecture' => '東京都',
            'sending_city' => '新宿区',
            'sending_address' => '1-1-1',
            'sending_building' => 'テストビル',
            'sending_phone_number' => '09012345678',
            'payment_method' => 'クレジットカード',
        ]);

        // 5. ログイン（自分の商品非表示用）
        $this->actingAs($user1);

        // 6. API に GET リクエスト
        $response = $this->getJson('/api/items');

        // 7. ステータスコード
        $response->assertStatus(200);

        // 8. 全商品取得（自分の商品は除外）
        $response->assertJsonFragment(['name' => '商品B']);  // 他人の商品
        $response->assertJsonFragment(['name' => '商品C']);  // 他人の商品
        $response->assertJsonMissing(['name' => '商品A']);   // 自分の商品は非表示

        // 9. SOLDラベル判定（購入済み）
        $responseData = $response->json('data'); // APIがdata配列で返す場合
        foreach ($responseData as $item) {
            if ($item['name'] === '商品C') {
                $this->assertArrayHasKey('sold', $item); // API側で'SOLD'用キーがある場合
                $this->assertTrue($item['sold']);       // sold=trueならSOLD
            }
            if ($item['name'] === '商品B') {
                $this->assertArrayNotHasKey('sold', $item); // 未購入
            }
        }
    }
}
