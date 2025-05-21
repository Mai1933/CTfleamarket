<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'user_id' => 1,
            'item_name' => '腕時計',
            'item_image' => 'watch.png',
            'brand' => 'アルマーニ',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => '良好',
            'price' => '15000',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_4gw7uEe5mdyfgbS145'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'item_name' => 'HDD',
            'item_image' => 'hdd.png',
            'brand' => '東芝',
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => '目立った傷や汚れなし',
            'price' => '5000',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_7sIaGQgdu8dV2l2fZ0'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'item_name' => '玉ねぎ３束',
            'item_image' => 'onion.png',
            'brand' => '田中ファーム',
            'description' => '新鮮な玉ねぎ３束のセット',
            'condition' => 'やや傷や汚れあり',
            'price' => '300',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_14k7uE6CUbq7e3K28b'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'item_name' => '革靴',
            'item_image' => 'shoes.png',
            'brand' => 'Crockett&Jones',
            'description' => 'クラシックなデザインの革靴',
            'condition' => '状態が悪い',
            'price' => '4000',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_28o2ak9P69hZ2l2aEI'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 1,
            'item_name' => 'ノートPC',
            'item_image' => 'pc.png',
            'brand' => 'NEC',
            'description' => '高性能なノートパソコン',
            'condition' => '良好',
            'price' => '45000',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_5kA16g7GY1Px8Jq7sx'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'item_name' => 'マイク',
            'item_image' => 'mike.png',
            'brand' => 'AKG',
            'description' => '高音質のレコーディング用マイク',
            'condition' => '目立った傷や汚れなし',
            'price' => '8000',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_6oEeX6d1iam3cZG006'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'item_name' => 'ショルダーバッグ',
            'item_image' => 'bag.png',
            'brand' => 'バレンシアガ',
            'description' => 'おしゃれなショルダーバッグ',
            'condition' => 'やや傷や汚れあり',
            'price' => '3500',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_5kAg1a1iAam3f7O6ov'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'item_name' => 'タンブラー',
            'item_image' => 'cup.png',
            'brand' => 'スターバックス',
            'description' => '使いやすいタンブラー',
            'condition' => '状態が悪い',
            'price' => '500',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_00gaGQ4uMfGnbVC14c'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'item_name' => 'コーヒーミル',
            'item_image' => 'coffeemill.png',
            'brand' => 'カリタ',
            'description' => '手動のコーヒーミル',
            'condition' => '良好',
            'price' => '4000',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_bIYaGQgdueCj5xedQZ'
        ];
        DB::table('items')->insert($param);
        $param = [
            'user_id' => 2,
            'item_name' => 'メイクセット',
            'item_image' => 'makeuptool.png',
            'brand' => '資生堂',
            'description' => '便利なメイクアップセット',
            'condition' => '目立った傷や汚れなし',
            'price' => '2500',
            'status' => 'stock',
            'url' => 'https://buy.stripe.com/test_8wMg1agdu79R7FmbII'
        ];
        DB::table('items')->insert($param);
    }
}
