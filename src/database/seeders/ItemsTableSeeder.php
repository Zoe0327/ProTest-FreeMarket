<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userA = User::where('email', 'userA@example.com')->first();
        $userB = User::where('email', 'userB@example.com')->first();

        $items = [
            [
                'user_id' => $userA->id,
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'item_img_url' => '腕時計.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => $userA->id,
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'item_img_url' => 'HDD.jpg',
                'condition_id' => 2,
            ],
            [
                'user_id' => $userA->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'item_img_url' => '玉ねぎ3束.jpg',
                'condition_id' => 3,
            ],
            [
                'user_id' => $userA->id,
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => null,
                'description' => 'クラシックなデザインの革靴',
                'item_img_url' => '革靴.jpg',
                'condition_id' => 4,
            ],
            [
                'user_id' => $userA->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'item_img_url' => 'ノートPC.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => $userB->id,
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => null,
                'description' => '高品質のレコーディング用マイク',
                'item_img_url' => 'マイク.jpg',
                'condition_id' => 2,
            ],
            [
                'user_id' => $userB->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'item_img_url' => 'ショルダーバッグ.jpg',
                'condition_id' => 3,
            ],
            [
                'user_id' => $userB->id,
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => null,
                'description' => '使いやすいタンブラー',
                'item_img_url' => 'タンブラー.jpg',
                'condition_id' => 4,
            ],
            [
                'user_id' => $userB->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'item_img_url' => 'コーヒーミル.jpg',
                'condition_id' => 1,
            ],
            [
                'user_id' => $userB->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'item_img_url' => '外出メイクアップセット.jpg',
                'condition_id' => 2,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
