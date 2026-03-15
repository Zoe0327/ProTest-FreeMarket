<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_item_with_required_fields_without_factories()
    {
        /** @var \App\Models\User $user */
        // ① ユーザー作成 & ログイン
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        // ② 条件とカテゴリを直接作成（Factoryなし）
        $condition = Condition::create(['condition' => '新品']);
        $category = Category::create(['category' => 'カテゴリA']);

        // ③ Itemを直接作成
        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテストの商品説明です。',
            'price' => 3000,
            'item_img_url' => '',
        ]);

        // ④ 多対多カテゴリの保存
        $item->categories()->attach($category->id);

        // ⑤ DBに正しく保存されているか確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテストの商品説明です。',
            'price' => 3000,
        ]);

        // ⑥ カテゴリとの関連も確認
        $this->assertTrue($item->categories->contains($category->id));
    }
}
