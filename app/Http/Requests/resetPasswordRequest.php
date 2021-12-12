<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class resetPasswordRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }
   
    public function rules()
    {
        return [
            "password" => "required | confirmed"
        ];
    }
}
