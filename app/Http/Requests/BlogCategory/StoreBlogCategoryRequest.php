<?php

namespace App\Http\Requests\BlogCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreBlogCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
                return [
                'name'        => 'required|string|max:255|unique:blog_categories,name',
                'slug'        => 'nullable|string|max:255|unique:blog_categories,slug',
                'description' => 'nullable|string',
                'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'status'      => 'required|in:active,inactive',
                'lang'        => 'nullable|string|max:5',
            ];
    }

                protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
