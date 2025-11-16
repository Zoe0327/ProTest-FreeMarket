<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategoryItemTableSeeder extends Seeder
{
    public function run()
    {
        $relations = [
            // 1商品 = 1カテゴリー の場合
            ['item_id' => 1, 'category_id' => 1],  // 腕時計 → ファッション
            ['item_id' => 2, 'category_id' => 2],  // HDD → 家電
            ['item_id' => 3, 'category_id' => 10], // 玉ねぎ → キッチン
            ['item_id' => 4, 'category_id' => 5],  // 革靴 → メンズ
            ['item_id' => 5, 'category_id' => 2],  // ノートPC → 家電
            ['item_id' => 6, 'category_id' => 2],  // マイク → 家電
            ['item_id' => 7, 'category_id' => 1],  // バッグ → ファッション
            ['item_id' => 8, 'category_id' => 10], // タンブラー → キッチン
            ['item_id' => 9, 'category_id' => 10], // コーヒーミル → キッチン
            ['item_id' => 10, 'category_id' => 6], // メイクセット → コスメ

            // 👇 複数カテゴリーに属する例
            ['item_id' => 4, 'category_id' => 1],  // 革靴 → ファッションも
            ['item_id' => 7, 'category_id' => 4],  // バッグ → メンズにも
        ];

        foreach ($relations as $relation) {
            DB::table('category_items')->insert([
                'item_id' => $relation['item_id'],
                'category_id' => $relation['category_id'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
