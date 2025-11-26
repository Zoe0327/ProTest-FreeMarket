<?php

namespace Tests\Feature\Items;

use App\Models\Condition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemPurchaseAddressTest extends TestCase
{
    use RefreshDatabase;

    /** @var Condition */
    protected $condition;
    /** @var Category */
    protected $category;
    
    protected function setUp(): void
    {
        parent::setUp();
        // 条件データを作成
        $this->condition = \App\Models\Condition::create([
            'condition' => '新品'
        ]);

        // カテゴリデータを作成（カラム名に注意）
        $this->category = \App\Models\Category::create([
            'category_name' => 'テストカテゴリ'
        ]);
    }

    /**
     * 住所変更後に商品購入画面に反映されるか
     */
    public function test_address_reflected_on_purchase_page()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成＆ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 既存の Item モデルを使って直接作成
        $item = \App\Models\Item::create([
            'name' => 'テスト商品',
            'description' => 'テスト用説明文',
            'price' => 1000,
            'item_img_url' => 'test.jpg',
            'category_id' => $this->category->id,
            'condition_id' => $this->condition->id,
            'user_id' => $user->id,
        ]);

        // 住所変更画面にPOST
        $this->post(route('purchase.address.update', ['item_id' => $item->id]), [
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区テスト町1-1-1',
            'building'  => 'テストビル101',
        ])->assertRedirect(route('purchase.create', ['item_id' => $item->id]));

        // 商品購入画面を再度GET
        $response = $this->get(route('purchase.create', ['item_id' => $item->id]));

        // 変更した住所が表示されていることを確認
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区テスト町1-1-1');
        $response->assertSee('テストビル101');
    }

    /**
     * 購入時に住所がsold_itemsに紐づくか
     */
    public function test_address_saved_when_item_purchased()
    {
        /** @var \App\Models\User $user */
        // ユーザー作成＆ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 既存の Item モデルを使って直接作成
        $item = \App\Models\Item::create([
            'name' => 'テスト商品',
            'description' => 'テスト用説明文',
            'price' => 1000,
            'item_img_url' => 'test.jpg',
            'category_id' => $this->category->id,
            'condition_id' => $this->condition->id,
            'user_id' => $user->id,
        ]);

        // 住所変更
        $this->post(route('purchase.address.update', ['item_id' => $item->id]), [
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区テスト町1-1-1',
            'building'  => 'テストビル101',
        ]);

        // 商品購入POST
        $postData = [
            'payment_method' => 'カード払い',
        ];

        $this->post(route('purchase.store', ['item_id' => $item->id]), $postData)
            ->assertRedirect(); // 購入後のリダイレクト確認

        // DBに正しく保存されていることを確認
        $this->assertDatabaseHas('sold_items', [
            'user_id'          => $user->id,
            'item_id'          => $item->id,
            'sending_post_code' => '123-4567',
            'sending_address'   => '東京都渋谷区テスト町1-1-1',
            'sending_building'  => 'テストビル101',
        ]);
    }
}
