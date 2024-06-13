<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;

class CalculateScoreService
{
    // $questionCount = 0
    // $availablePoints = 0
    // $points = 0
    // $answers = $request->answers
    // $quizId = $request->id
    // foreach ($answers as $answer)
    // $currentAnswer = Answer::find($answer->answer)


    // $currentQuestion = Question::find($answer->question)

    // $questionCount++
    // how many points it's worth - $questionPoints = $currentQuestion->points
    // $availablePoints += $questionPoints
    // if correct
    // $points += $questionPoints
    // $correctCount++

    // return array ['question_count' => $questionCount, 'correct_count' => $correctCount, 'available_points' => $availablePoints
    // , 'points' => $points]

    // If any answers incorrect = 0 points,
    // If only correct answers selected = full marks

    //Louis
    /*
     * Get results (array of objects) (just includes ones ticked)
     * Return all questions relating to quiz_id
     * Maybe put them into an array
     * Using question id, get back a list of all answers from db
     * Alternatively get just ones with correct = 1 and make an array
     * get ones with correct = 0 as a separate array
     * Then we check the ones with 'correct = 1' have a response in results
     * And we check the ones with 'correct = 0' don't have a response
     */

    static function calculateScore($quizId, $answers)
    {
        $questions = Question::with('answers')->where('quiz_id', '=', $quizId)->get();
        $answerArray = [];

        foreach ($questions as $question) {
            $answerArray[] = Answer::where('question_id', '=', $question->id)->get();
                //->where('correct', '=', 1)->select('id')->get();

            foreach ($answerArray as $answer) {
                if ($answers->question_id === $question->id) {
                    $p = $questions->answers->id;
                }
            }

        }

        $correctCount = 0;

//        for ($i = 0; $i < count($correctAnswerArray); $i++) {
//            for ($j = 0; $j < count($submittedAnswerIds); $j++) {
//                if ($correctAnswerArray[$i] === $submittedAnswerIds[$j]) {
//                    $correctCount++;
//                }
//            }
//        }

        for ($i = 0; $i < count($questions); $i++) {
            for ($j = 0; $j < count($answerArray); $j++) {
                if ($answerArray[$j]->question_id === $questions[$i]->id) {
                    $relevantAnswerArray[] = $answerArray[$j];
                }
            }
        }

        $questionCount = 0;
        $pointCount = 0;

        foreach ($questions as $question) {
            $questionCount++;
            $pointCount += $question->points;
        }

        return $relevantAnswerArray;
    }

}
