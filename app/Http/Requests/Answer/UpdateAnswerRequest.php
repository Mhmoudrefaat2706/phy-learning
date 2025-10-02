<?php

namespace App\Http\Requests\Answer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnswerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level_id' => 'required|exists:levels,id',
            'question_text' => 'required|string|max:255',
            'answers' => 'required|array|min:2',
            'answers.*.answer' => 'required|string|max:255',
            'answers.*.id' => 'nullable|exists:answers,id',
            'correct_answer_index' => 'required|integer|min:0'
        ];
    }

    public function messages()
    {
        return [
            'level_id.required' => 'Level is required',
            'level_id.exists' => 'Selected level does not exist',
            'question_text.required' => 'Question text is required',
            'answers.required' => 'At least two answers are required',
            'answers.*.answer.required' => 'Each answer is required',
            'correct_answer_index.required' => 'You must select the correct answer'
        ];
    }
}
