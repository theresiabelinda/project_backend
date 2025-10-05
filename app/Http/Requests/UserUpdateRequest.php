<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        // Mendapatkan ID user dari route parameter
        $userId = $this->route('id');

        return [
            "name" => ['sometimes', 'max:100'],
            // Ignore username user saat ini saat cek unik
            "username" => ['sometimes', 'max:100', Rule::unique('users')->ignore($userId)],
            "email" => ['sometimes', 'email', Rule::unique('users')->ignore($userId)],
            "password" => ['sometimes', 'max:100', 'confirmed'],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(['errors' => $validator->getMessageBag()], 400));
    }
}
