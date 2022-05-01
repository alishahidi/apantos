<?php

namespace App\Http\Requests\Admin;

use System\Request\Request;

class PostRequest extends Request
{
    public function rules()
    {
        if (methodField() == "put") // for update
            return [
                "rules" => [
                    "title" => "required|max:210",
                    "description" => "required|max:380",
                    "body" => "required",
                    "cat_id" => "required|exists:categories,id",
                    "image" => "file|mimes:jpeg,jpg,png,gif",
                ],
            ];

        return [
            "rules" => [
                "title" => "required|max:210",
                "description" => "required|max:380",
                "body" => "required",
                "cat_id" => "required|exists:categories,id",
                "image" => "required|file|mimes:jpeg,jpg,png,gif",
            ],
        ];
    }
}
