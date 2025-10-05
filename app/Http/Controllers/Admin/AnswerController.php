<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Answer;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Question;
use App\Http\Requests\Answer\StoreAnswerRequest;
use App\Http\Requests\Answer\UpdateAnswerRequest;

class AnswerController extends Controller
{

    public function index()
    {

        $answers   = Answer::with('question')->orderBy('created_at', 'desc');
        $questions = Question::orderBy('created_at','desc')->paginate(8);
        $levels    = Level::all();
        return view('admin.pages.answer.list', compact('answers','questions','levels'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $questions = Question::with('answers', 'level')
            ->where('question', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return response()->json($questions);
    }

public function store(StoreAnswerRequest $request)
{
    $validated = $request->validated();

    $question = Question::create([
        'level_id' => $validated['level_id'],
        'question' => $validated['question_text'],
    ]);

    $ids = [];
    foreach ($validated['answers'] as $ans) {
        $ids[] = Answer::create([
            'question_id' => $question->id,
            'answer' => $ans,
        ])->id;
    }

    $correctIndex = $validated['correct_answer'];
    $correctId = $ids[$correctIndex] ?? $ids[0];

    $question->update(['answer_id' => $correctId]);
    $question->load('answers', 'level');

    return response()->json([
        'success' => true,
        'answers' => Answer::whereIn('id', $ids)->get(),
        'question' => $question,
        'correct_answer_id' => $correctId,
    ]);
}


    public function edit(Question $question) {
        $question->load('answers', 'level');
        return response()->json([
            'question' => $question,
            'answers' => $question->answers
        ]);
    }
public function update(UpdateAnswerRequest $request, Question $question)
{
    $validated = $request->validated();

        $question->update([
            'level_id' => $request->level_id,
            'question' => $request->question_text,
        ]);

        $answers = [];

        foreach($request->answers as $i => $a){
            if(isset($a['id'])){
                $answer = Answer::find($a['id']);
                $answer->update(['answer' => $a['answer']]);
            } else {
                $answer = Answer::create([
                    'question_id' => $question->id,
                    'answer' => $a['answer'],
                ]);
                $a['id'] = $answer->id;
            }
            $answers[$i] = $a['id'];
        }
        $correctIndex = $request->correct_answer_index;
        $question->answer_id = $answers[$correctIndex] ?? $answers[0];
        $question->save();

        return response()->json([
            'success' => true,
            'question' => $question->load('level'),
            'answers' => $question->answers,
            'correct_answer_id' => $question->answer_id
        ]);
    }



    public function destroy(Question $question): JsonResponse
    {
        $question->update(['answer_id' => null]);
        $question->answers()->delete();
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question and its answers deleted successfully.'
        ]);
    }
}
