<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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

    public function test_addQuiz_success(): void
    {
        $testData = ['name' => 'Test task', 'description' => 'Test, test'];

        $response = $this->postJson('/api/quizzes', $testData);
        $response->assertStatus(201)
            ->assertJson(function(AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('quizzes', $testData);
    }

    public function test_addQuiz_failureNameRequired(): void
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

    public function test_addQuiz_failureDescriptionRequired(): void
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

    public function test_addQuiz_malformedName(): void
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

    public function test_addQuiz_malformedDescription(): void
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

    public function test_getSingleQuiz_success(): void
    {
        Answer::factory()->count(5)->create();
        $response = $this->get('/api/quizzes/1');
        $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'data'])
            ->has('data', function (AssertableJson $json) {
                $json->hasAll('id', 'name', 'description', 'questions')
                ->has('questions', 1,  function (AssertableJson $json) {
                    $json->hasAll('id', 'question', 'hint', 'points', 'quiz_id', 'answers')
                    ->has('answers', 1, function (AssertableJson $json) {
                        $json->hasAll('id', 'answer', 'feedback', 'correct', 'question_id');
                    });
                });
            });
        });
    }
}
