<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

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
