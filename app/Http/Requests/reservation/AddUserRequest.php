<?php

namespace App\Http\Requests\reservation;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ];
    }
}
