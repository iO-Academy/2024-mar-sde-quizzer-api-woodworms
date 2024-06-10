<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class QuizAPIController extends Controller
{
    function getQuizzes()
    {
//        if (class_exists('Quiz')) {
            $quizzes = Quiz::all();

            if ($quizzes->isEmpty()) {
                return response()->json([
                    "message" => "No quizzes found"
                ]);
            }
            return response()->json([
                'message' => 'Quizzes retrieved',
                'data' => $quizzes
            ], 200);
//        }
//        else {
//            return response()->json([
//                'message' => 'Quizzes not found, please check database connection',
//            ], 500);
//        }
    }
}
