<?php

namespace App\Http\Requests\Auth;

use System\Request\Request;

class LoginRequest extends Request
{
    public function rules()
    {
        return [
            'rules' => [
                'email' => 'required|email',
                'password' => 'required',
            ],
            'errors' => [
                'email' => 'required!some custom error|email!some custom error',
            ],
        ];
    }
}
