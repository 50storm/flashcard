<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

// php artisan db:seed --class=TagSeeder
class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 英検3〜1級、TOEIC、IELTSのタグを追加
        $tags = [
            ['tag' => '英検3級'],
            ['tag' => '英検準2級'],
            ['tag' => '英検2級'],
            ['tag' => '英検準1級'],
            ['tag' => '英検1級'],
            ['tag' => 'TOEIC'],
            ['tag' => 'IELTS'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
