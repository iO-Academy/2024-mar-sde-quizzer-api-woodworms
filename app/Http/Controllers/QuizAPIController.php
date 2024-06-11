<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

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
            'name' => 'required|string|max:128',
            'description' => 'required|string|max:1000'
        ]);

        try {
            $quiz = new Quiz();
            $quiz->name = $request->name;
            $quiz->description = $request->description;
            $quiz->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Quiz creation failed'
            ], 500);
        }

            return response()->json([
                'message' => 'Quiz created',
            ],201);
        }
}
