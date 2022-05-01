<?php

namespace App\Http\Requests\Auth;

use System\Request\Request;

class RegisterRequest extends Request
{
    public function rules()
    {
        return [
            "rules" => [
                "name" => "required|max:210",
                "email" => "required|max:90|email|unique:users,email",
                "avatar" => "required|file|mimes:jpeg,jpg,png,gif|max:2048",
                "password" => "required|min:8|confirmed",
                "bio" => "required|max:380"
            ],
            "errors" => [
                "email" => "required!some custom error|email!some custom error",
            ]
        ];
    }
}
