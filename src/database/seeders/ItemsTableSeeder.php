<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'item_name' => '腕時計',
            'item_image' => 'watch.png',
            'brand' => 'アルマーニ',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => '良好',
            'price' => '15000',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => 'HDD',
            'item_image' => 'hdd.png',
            'brand' => '東芝',
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => '目立った傷や汚れなし',
            'price' => '5000',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => '玉ねぎ３束',
            'item_image' => 'onion.png',
            'brand' => '田中ファーム',
            'description' => '新鮮な玉ねぎ３束のセット',
            'condition' => 'やや傷や汚れあり',
            'price' => '300',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => '革靴',
            'item_image' => 'shoes.png',
            'brand' => 'Crockett&Jones',
            'description' => 'クラシックなデザインの革靴',
            'condition' => '状態が悪い',
            'price' => '4000',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => 'ノートPC',
            'item_image' => 'pc.png',
            'brand' => 'NEC',
            'description' => '高性能なノートパソコン',
            'condition' => '良好',
            'price' => '45000',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => 'マイク',
            'item_image' => 'mike.png',
            'brand' => 'AKG',
            'description' => '高音質のレコーディング用マイク',
            'condition' => '目立った傷や汚れなし',
            'price' => '8000',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => 'ショルダーバッグ',
            'item_image' => 'bag.png',
            'brand' => 'バレンシアガ',
            'description' => 'おしゃれなショルダーバッグ',
            'condition' => 'やや傷や汚れあり',
            'price' => '3500',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => 'タンブラー',
            'item_image' => 'cup.png',
            'brand' => 'スターバックス',
            'description' => '使いやすいタンブラー',
            'condition' => '状態が悪い',
            'price' => '500',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => 'コーヒーミル',
            'item_image' => 'coffeemill.png',
            'brand' => 'カリタ',
            'description' => '手動のコーヒーミル',
            'condition' => '良好',
            'price' => '4000',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
        $param = [
            'item_name' => 'メイクセット',
            'item_image' => 'makeuptool.png',
            'brand' => '資生堂',
            'description' => '便利なメイクアップセット',
            'condition' => '目立った傷や汚れなし',
            'price' => '2500',
            'status' => 'stock',
        ];
        DB::table('items')->insert($param);
    }
}
