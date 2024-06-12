<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Exception;
use Illuminate\Http\Request;


class AnswerAPIController extends Controller
{
    function addAnswer($question_id, Request $request)
    {
//        $id = Answer::with(['questions' => ['answers'],])->find($question_id);

        $request->validate([
            'answer' => 'required|string|max:500',
//            'feedback' => 'required|string|max:1000'
        ]);

        try {
            $answer = new Answer();
            $answer->question_id = $question_id;
            $answer->answer = $request->answer;
//            $answer->feedback = $request->feedback;
            $answer->correct = $request->correct;
//            $answer->updated_at = $request->updated_at;
//            $answer->created_at = $request->created_up;

            $result = $answer->save();
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

