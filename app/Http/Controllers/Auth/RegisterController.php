<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\ImageUpload;
use App\Http\Services\Mail;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use System\Auth\Auth;
use System\Security\Security;

class RegisterController extends Controller
{
    public function register()
    {
        $request = new RegisterRequest();
        $inputs = $request->all();
        $inputs["avatar"] = ImageUpload::uploadAndFit("avatar", "user_avatar", 151, 119);
        $inputs['permission'] = 'user';
        Auth::storeUser($inputs, "password");
        flash("user_activation_send", "Successfully registered.");
        return redirect(route("auth.login"));
    }
}
