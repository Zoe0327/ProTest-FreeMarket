<?php

namespace Tests\Feature\Items;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_is_displayed_on_purchase_page()
    {
        
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $condition = Condition::create([
            'condition' => '良好',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'brand_name' => 'テストブランド',
            'item_img_url' => 'test.jpg',
        ]);

         /** @var \App\Models\User $buyer */
        $response = $this->actingAs($buyer)
            ->get(route('purchases.create', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('支払い方法');
    }
}