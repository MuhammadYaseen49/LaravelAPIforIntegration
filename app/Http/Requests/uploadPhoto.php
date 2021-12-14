<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class uploadPhoto extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'name' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'privacy' => 'required'
        ];
    }
}
