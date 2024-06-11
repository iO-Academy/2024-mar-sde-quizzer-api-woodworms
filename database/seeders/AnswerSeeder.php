<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    for ($i = 1; $i <= 3; $i++) {
        DB::table('answer')->insert([
            'answer' => Str::random(10),
            'feedback' => Str::random(20),
            'correct' => rand(0,1),
        ]);
    }
}
}
