<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        // Login akan menggunakan username dan password
        return [
            "username" => ['required', 'max:100'],
            "password" => ['required', 'max:100'],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(['errors' => $validator->getMessageBag()], 400));
    }
}
