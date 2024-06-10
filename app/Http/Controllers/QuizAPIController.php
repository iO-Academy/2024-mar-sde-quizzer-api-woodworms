<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizAPIController extends Controller
{
    function getQuizzes()
    {
        $quizzes = Quiz::all();
        return response()->json([
            'message' => 'Quizzes retrieved',
            'data' => $quizzes
        ], 200);
    }
}
