<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
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
        }
        catch (\Exception) {
            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
