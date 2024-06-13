<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;


class AnswerAPIController extends Controller
{
    function addAnswer(Request $request)
    {
        $request->validate([
            'answer' => 'required|string|max:500',
            'feedback' => 'string|max:1000',
            'question_id' => 'required|integer|exists:questions,id',
            'correct' => 'required|boolean',
        ]);

        try {
            $question = Question::find($request->question_id);
            $answer = new Answer();
            $answer->answer = $request->answer;
            $answer->feedback = $request->feedback;
            $answer->question_id = $request->question_id;
            $answer->correct = $request->correct;

            $result = $question->answers()->save($answer);
        } catch (Exception $e) {
            $result = false;
        }

        if ($result) {
            return response()->json([
                'message' => 'Answer created',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Answer creation failed'
            ], 500);
        }
    }
}
