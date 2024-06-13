<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Services\CalculateScoreService;
use Exception;
use Illuminate\Http\Request;

class QuizAPIController extends Controller
{
    function getQuizzes()
    {
        try {
            $quizzes = Quiz::all();
            return response()->json([
                'message' => 'Quizzes retrieved',
                'data' => $quizzes
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }
    }

    function addQuiz(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        try {
            $quiz = new Quiz();
            $quiz->name = $request->name;
            $quiz->description = $request->description;
            $result = $quiz->save();
        } catch (Exception $e) {
            $result = false;
        }

        if ($result) {
            return response()->json([
                'message' => 'Quiz created',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Quiz creation failed'
            ], 500);
        }
    }

    function singleQuiz(int $id)
    {
        try {
            $quiz = Quiz::with(['questions' => ['answers'],])->find($id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }

        if (!is_null($quiz)) {
            return response()->json([
                'message' => 'Quiz retrieved',
                'data' => $quiz
            ], 200);
        } else {
            return response()->json([
                'message' => 'Quiz not found'
            ], 404);
        }
    }

    function calculateScore(Request $request) {

        try {
            CalculateScoreService::calculateScore($request);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'misc error'
            ], );
        }

        return response()->json([
            'message' => 'score calculated',
        ]);
    }
}
