<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class CreateAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

public function rules()
{
    return [
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|unique:admins,email',
        'phone'     => 'nullable|string|max:20',
        'password'  => 'required|string|min:8|confirmed',
        'role_id'   => 'required|exists:roles,id',
        'status'    => 'nullable|in:active,inactive',
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
