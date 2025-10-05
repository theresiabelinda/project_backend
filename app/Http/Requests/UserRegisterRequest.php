<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            "name" => ['required', 'max:100'],
            "username" => ['required', 'max:100', 'unique:users,username'],
            "email" => ['required', 'email', 'unique:users,email'],
            "password" => ['required', 'max:100', 'confirmed'], // Membutuhkan password_confirmation
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(['errors' => $validator->getMessageBag()], 400));
    }
}

