<?php

namespace Tests\Feature\Items;

use App\Models\Condition;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemPurchaseAddressTest extends TestCase
{
    use RefreshDatabase;

    protected $condition;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // 条件データ作成
        $this->condition = Condition::create([
            'condition' => '新品',
        ]);

        // カテゴリデータ作成
        $this->category = Category::create([
            'category_name' => 'テストカテゴリ',
        ]);
    }

    /**
     * 住所変更後に商品購入画面に反映されるか
     */
    public function test_address_reflected_on_purchase_page()
    {
        // 出品者
        $seller = User::factory()->create();

        // 購入者
        $buyer = User::factory()->create();

        // 商品作成（出品者が所有）
        $item = Item::create([
            'name' => 'テスト商品',
            'description' => 'テスト用説明文',
            'price' => 1000,
            'item_img_url' => 'test.jpg',
            'category_id' => $this->category->id,
            'condition_id' => $this->condition->id,
            'user_id' => $seller->id,
        ]);
        /** @var \App\Models\User $buyer */
        // 購入者でログイン＆セッションに住所をセット
        $this->actingAs($buyer)
            ->withSession([
                'purchase_address' => [
                    'post_code' => '123-4567',
                    'address' => '東京都渋谷区テスト町1-1-1',
                    'building' => 'テストビル101',
                ],
            ]);

        // 住所変更画面にPOST
        $response = $this->post(route('purchases.address.update', ['item_id' => $item->id]), [
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区テスト町1-1-1',
            'building'  => 'テストビル101',
        ]);

        // リダイレクト確認
        $response->assertRedirect(route('purchases.create', ['item_id' => $item->id]));

        // 購入画面を取得
        $purchasePage = $this->get(route('purchases.create', ['item_id' => $item->id]));

        // 住所が表示されていることを確認
        $purchasePage->assertSee('123-4567');
        $purchasePage->assertSee('東京都渋谷区テスト町1-1-1');
        $purchasePage->assertSee('テストビル101');
    }

    /**
     * 購入時に住所が sold_items に保存されるか
     */
    public function test_address_saved_when_item_purchased()
    {
        // 出品者
        $seller = User::factory()->create();

        // 購入者
        $buyer = User::factory()->create();

        // 商品作成
        $item = Item::create([
            'name' => 'テスト商品',
            'description' => 'テスト用説明文',
            'price' => 1000,
            'item_img_url' => 'test.jpg',
            'category_id' => $this->category->id,
            'condition_id' => $this->condition->id,
            'user_id' => $seller->id,
        ]);

        /** @var \App\Models\User $buyer */
        // 購入者でログイン＆住所セッションをセット
        $this->actingAs($buyer)
            ->withSession([
                'purchase_address' => [
                    'post_code' => '123-4567',
                    'address' => '東京都渋谷区テスト町1-1-1',
                    'building' => 'テストビル101',
                ],
            ]);

        // 商品購入
        $postData = [
            'payment_method' => 'カード払い',
        ];

        $this->post(route('purchases.store', ['item_id' => $item->id]), $postData)
            ->assertRedirect(route('items.index'));

        // DBに保存されているか確認
        $this->assertDatabaseHas('sold_items', [
            'user_id'           => $buyer->id,
            'item_id'           => $item->id,
            'sending_post_code' => '123-4567',
            'sending_address'   => '東京都渋谷区テスト町1-1-1',
            'sending_building'  => 'テストビル101',
        ]);
    }
}
