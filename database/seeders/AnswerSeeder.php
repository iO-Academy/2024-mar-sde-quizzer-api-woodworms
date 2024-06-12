<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnswerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            for ($j = 1; $j <= 2; $j++) {
                DB::table('answers')->insert([
                    'answer' => Str::random(10),
                    'feedback' => Str::random(20),
                    'is_correct' => rand(0,1),
                    'question_id' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
