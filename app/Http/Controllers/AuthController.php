<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


/**
* O controlador AuthController.php contém um método login que é responsável por autenticar um usuário e gerar um token de acesso.
* Ele recebe as credenciais do usuário (email e senha) na requisição POST e retorna um JSON com o token de acesso e informações do usuário.
* O token é gerado utilizando o método createToken da classe User e é retornado no formato Bearer {token} no header da resposta.
*/

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
