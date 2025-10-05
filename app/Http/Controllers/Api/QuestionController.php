<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Question;
use App\Models\Level;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function getAllQuestions(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $questions = Question::with('answers')
            ->where('level_id', $user->level_id)
            ->get();

        $formattedQuestions = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question' => $question->question,
                'answers' => $question->answers->map(function ($answer) {
                    return [
                        'id' => $answer->id,
                        'answer' => $answer->answer,
                    ];
                })->toArray()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedQuestions
        ]);
    }

    public function evaluateAnswers(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $userAnswers = $request->input('answers', []);
        if (empty($userAnswers)) {
            return response()->json([
                'success' => false,
                'message' => 'No answers provided'
            ], 400);
        }

        $questions = Question::with('answers')->where('level_id', $user->level_id)->get();

        $correctCount = 0;
        $wrongCount = 0;
        $total = $questions->count();

foreach ($questions as $question) {
    $correctAnswerId = $question->answer_id;
    $userAnswer = $userAnswers[$question->id] ?? null;

    if ($correctAnswerId && $userAnswer == $correctAnswerId) {
        $correctCount++;
    } else {
        $wrongCount++;
    }
}


       $percentage = $total > 0 ? ($correctCount / $total) * 100 : 0;

if ($percentage >= 75) {
    $nextLevel = Level::where('id', '>', $user->level_id)->orderBy('id')->first();

    if ($nextLevel) {
        $user->level_id = $nextLevel->id;
        if ($user->score == 0) {
        $user->score = $percentage;
        } else {
            $user->score = ($user->score + $percentage) / 2;
        }
        $user->save();
    }else {
           $user->score = ($user->score + $percentage) / 2;

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'You have successfully completed all levels.',
                'total_questions' => $total,
                'correct' => $correctCount,
                'wrong' => $wrongCount,
                'percentage' => $percentage,
                'level_id' => $user->level_id,
                'score' => $user->score
            ]);
        }
    }




        return response()->json([
            'success' => true,
            'total_questions' => $total,
            'correct' => $correctCount,
            'wrong' => $wrongCount,
            'percentage' => $percentage,
            'level_id' => $user->level_id,
            'score' => $user->score
        ]);
    }
}
