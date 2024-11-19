<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        // サンプルの日本語データ
        $titles = [
            'お知らせタイトル1',
            '緊急のお知らせ',
            'システムメンテナンスのお知らせ',
            'サービス更新情報',
            '新機能リリース'
        ];

        $contents = [
            'この度、新しい機能を追加しました。',
            'システムメンテナンスを実施いたします。',
            '緊急メンテナンスのお知らせです。',
            'サービス利用規約が変更されました。',
            'お客様への感謝の気持ちを込めて、特別キャンペーンを実施します。'
        ];

        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('notices')->insert([
                'title' => $titles[array_rand($titles)], // ランダムな日本語タイトル
                'content' => $contents[array_rand($contents)], // ランダムな日本語コンテンツ
                'start_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'end_date' => $faker->dateTimeBetween('+1 month', '+2 months'),
                'is_active' => $faker->boolean,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
