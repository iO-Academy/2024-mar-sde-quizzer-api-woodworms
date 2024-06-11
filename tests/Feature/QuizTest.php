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

    public function test_createTodo_success(): void
    {
        $testData = ['name' => 'Test task', 'description' => 'Test, test'];

        $response = $this->postJson('/api/quizzes', $testData);
        $response->assertStatus(201)
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('quizzes', $testData);
    }

    public function test_createTodo_failureNameRequired(): void
    {
        $testData = ['description' => 'Test, test'];

        $response = $this->postJson('/api/quizzes', $testData);
        $response->assertStatus(422)
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'errors'])
                    ->has('errors', function (AssertableJson $json) {
                        $json->hasAll( 'name');
            });
        });
    }

    public function test_createTodo_failureDescriptionRequired(): void
    {
        $testData = ['name' => 'Test'];

        $response = $this->postJson('/api/quizzes', $testData);
        $response->assertStatus(422)
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'errors'])
                    ->has('errors', function (AssertableJson $json) {
                        $json->hasAll( 'description');
                    });
            });
    }

    public function test_createTodo_malformedName(): void
    {
        $testData = ['name' => 10, 'description' => 'Test'];

        $response = $this->postJson('/api/quizzes', $testData);
        $response->assertStatus(422)
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'errors'])
                    ->has('errors', function (AssertableJson $json) {
                        $json->hasAll( 'name');
                    });
            });
    }

    public function test_createTodo_malformedDescription(): void
    {
        $testData = ['name' => 'Test', 'description' => 10];

        $response = $this->postJson('/api/quizzes', $testData);
        $response->assertStatus(422)
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message', 'errors'])
                    ->has('errors', function (AssertableJson $json) {
                        $json->hasAll( 'description');
                    });
            });
    }

}
