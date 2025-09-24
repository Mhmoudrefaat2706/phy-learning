<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name'        => 'required|string|max:255',
            'about_us'         => 'nullable|string',
            'lang'             => 'nullable|string|max:10',
            'google_analeteces'=> 'nullable|string',
            'type_description' => 'nullable|string',
            'keywordes'        => 'nullable|string',
            'meta_description' => 'nullable|string',
            'url'              => 'nullable|url',
            'maintenance_mode' => 'boolean',
            'facebook_pixel'   => 'nullable|string',
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422));
    }
}
