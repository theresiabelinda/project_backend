<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            // 'token' akan ditambahkan secara manual di Controller saat Register/Login
            $this->mergeWhen(isset($this->token), [
                'token' => $this->token,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
