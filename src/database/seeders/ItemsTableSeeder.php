<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'item_img_url' => '腕時計.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'item_img_url' => 'HDD.jpg',
                'condition_id' => 2,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'item_img_url' => '玉ねぎ3束.jpg',
                'condition_id' => 3,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => null,
                'description' => 'クラシックなデザインの革靴',
                'item_img_url' => '革靴.jpg',
                'condition_id' => 4,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'item_img_url' => 'ノートPC.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => null,
                'description' => '高品質のレコーディング用マイク',
                'item_img_url' => 'マイク.jpg',
                'condition_id' => 2,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'item_img_url' => 'ショルダーバッグ.jpg',
                'condition_id' => 3,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => null,
                'description' => '使いやすいタンブラー',
                'item_img_url' => 'タンブラー.jpg',
                'condition_id' => 4,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'item_img_url' => 'コーヒーミル.jpg',
                'condition_id' => 1,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'item_img_url' => '外出メイクアップセット.jpg',
                'condition_id' => 2,
            ],
        ];

        foreach ($items as $item) {
            DB::table('items')->insert([
                'name' => $item['name'],
                'price' => $item['price'],
                'brand_name' => $item['brand_name'],
                'description' => $item['description'],
                'item_img_url' => $item['item_img_url'],
                'condition_id' => $item['condition_id'],
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
