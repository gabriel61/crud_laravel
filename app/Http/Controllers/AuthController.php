<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $user = User::where("email", $request["email"])->first();
        $token = $user->createToken("authToken")->plainTextToken;

        return response()->json([
            "access_token"=>$token,
            "token_type"=>"Bearer",
            "user"=>$user
        ]);
    }

}
