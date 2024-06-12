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
                    $json->hasAll('id', 'question', 'hint', 'points', 'answers')
                    ->has('answers', 1, function (AssertableJson $json) {
                        $json->hasAll('id', 'answer', 'feedback', 'correct');
                    });
                });
            });
        });
    }

    public function test_getSingleQuiz_failure(): void
    {
        Answer::factory()->create();
        $response = $this->get('/api/quizzes/500');
        $response->assertStatus(404)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message']);
        });
    }

    public function test_addQuestion_success(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Test question', 'points' => 2, 'quiz_id' => 1, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(201)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message']);
        });

        $this->assertDatabaseHas('questions', $testData);
    }

    public function test_addQuestion_failurePointsRequired(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Test question', 'quiz_id' => 1, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('points');
            });
        });
    }

    public function test_addQuestion_failureQuiz_idRequired(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Test question', 'points' => 2, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('quiz_id');
            });
        });
    }

    public function test_addQuestion_failureQuestionRequired(): void
    {
        Quiz::factory()->create();
        $testData = ['quiz_id' => 1, 'points' => 2, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('question');
            });
        });
    }

    public function test_addQuestion_malformedQuestion(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 1, 'quiz_id' => 1, 'points' => 2, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('question');
            });
        });
    }

    public function test_addQuestion_malformedQuiz_id(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Question Test', 'quiz_id' => 'one', 'points' => 2, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('quiz_id');
            });
        });
    }

    public function test_addQuestion_malformedPoints(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Question Test', 'quiz_id' => 1, 'points' => 'two', 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('points');
            });
        });
    }

    public function test_addQuestion_malformedHint(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Question Test', 'quiz_id' => 1, 'points' => 2, 'hint' => 1];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('hint');
            });
        });
    }

    public function test_addQuestion_zeroPoints(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Question Test', 'quiz_id' => 1, 'points' => 0, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('points');
            });
        });
    }

    public function test_addQuestion_negativePoints(): void
    {
        Quiz::factory()->create();
        $testData = ['question' => 'Question Test', 'quiz_id' => 1, 'points' => -5, 'hint' => 'hello'];

        $response = $this->postJson('/api/questions', $testData);
        $response->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors'])
            ->has('errors', function (AssertableJson $json) {
                $json->hasAll('points');
            });
        });
    }
}
