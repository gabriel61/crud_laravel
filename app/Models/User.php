<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/*
Este é o modelo para a tabela de usuários do banco de dados.

O modelo contém propriedades que definem:
- Quais atributos do usuário podem ser atribuídos em massa (definidos como fillable),
- Quais atributos devem ser ocultos quando o usuário é serializado (definidos como hidden) e
- Quais atributos devem ser convertidos em tipos de dados específicos (definidos como casts).

Este modelo usa o trait HasApiTokens fornecido pelo Laravel Passport,
que permite que os usuários gerem tokens de acesso para autenticar solicitações à API.

Além disso, ele usa o trait HasFactory, que fornece uma maneira simples de criar objetos de modelo,
e o trait Notifiable, que permite que o usuário receba notificações.

o modelo estende a classe Authenticatable, que fornece a funcionalidade de autenticação do Laravel e
implementa a interface Illuminate\Contracts\Auth\Authenticatable.
*/


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


}
