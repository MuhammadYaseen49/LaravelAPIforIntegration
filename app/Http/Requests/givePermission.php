<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class givePermission extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'access_to' => 'required|email',
            'address' => 'required'
        ];
    }
}
