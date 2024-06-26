<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;

class QuestionAPIController extends Controller
{
    function addQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'points' => 'required|integer|min:1',
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'hint' => 'string',
        ]);

        try {
            $quiz = Quiz::find($request->quiz_id);
            $question = new Question();
            $question->question = $request->question;
            $question->points = $request->points;
            $question->quiz_id = $request->quiz_id;
            $question->hint = $request->hint;

            $result = $quiz->questions()->save($question);
        } catch (Exception $e) {
            $result = false;
        }

        if ($result) {
            return response()->json([
                'message' => 'Question created',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Question creation failed'
            ], 500);
        }
    }
}
