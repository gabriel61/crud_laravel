<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


/*
A primeira rota define o endpoint `/login` para o método `login` do `AuthController` através do método `post()`.
A segunda rota define o endpoint `/users` para o método `create` do `UserController` através do método `post()`.
Essa rota não utiliza middleware de autenticação, pois é responsável por criar novos usuários.

O middleware `auth:sanctum` é utilizado para proteger as rotas que seguem.
Esse middleware exige que o usuário esteja autenticado para acessar essas rotas.

As rotas dentro do grupo protegido por middleware definem os endpoints `/users`, `/users/{id}`, `/users/{id}` e `/users/{id}`
respectivamente para os métodos `index`, `show`, `update` e `destroy` do `UserController`.
Essas rotas são acessíveis apenas para usuários autenticados.
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'create']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

