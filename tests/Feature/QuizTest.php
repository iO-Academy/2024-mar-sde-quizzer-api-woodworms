<?php

namespace Tests\Feature;

use App\Http\Controllers\QuizAPIController;
use App\Models\Quiz;
use Database\Factories\QuizFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use DatabaseMigrations;
    public function test_getAllQuizzes_success(): void
    {
        Quiz::factory()->create();
        $response = $this->get('/api/quizzes');
        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'data'])
            ->has('data', 1, function (AssertableJson $json) {
                $json->hasAll('id', 'name', 'description');
            });
        });
    }
}
