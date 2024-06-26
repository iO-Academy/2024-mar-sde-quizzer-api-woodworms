<?php

use App\Http\Controllers\AnswerAPIController;
use App\Http\Controllers\QuestionAPIController;
use App\Http\Controllers\QuizAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/quizzes', [QuizAPIController::class, 'getQuizzes']);
Route::post('/quizzes', [QuizAPIController::class, 'addQuiz']);
Route::get('/quizzes/{id}', [QuizAPIController::class, 'singleQuiz']);
Route::post('/answers', [AnswerAPIController::class, 'addAnswer']);
Route::post('/questions', [QuestionAPIController::class, 'addQuestion']);
