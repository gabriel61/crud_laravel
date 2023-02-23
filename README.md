# CRUD de usuários com senha criptografada utilizando ↯

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 💻 Sobre o projeto

Este projeto é um exemplo de um CRUD (Create, Read, Update, Delete) em Laravel com autenticação por token. Ele permite que um usuário autenticado execute operações CRUD em uma lista de usuários armazenados em um banco de dados.

O projeto utiliza o Laravel e inclui um arquivo de rotas em routes/api.php, um controlador UserController.php para gerenciar as operações CRUD e um controlador AuthController.php para lidar com a autenticação de usuários e a geração de tokens de acesso.

## ⚒ Requisitos
- PHP >= 7.3
- Composer
- Banco de dados MySQL
- Postman (opcional, para testar a API)

## 🎮 Instalação
1. Clone o repositório do projeto para um diretório local
2. Abra o terminal na pasta raiz do projeto e execute o comando `composer install` para instalar as dependências do Laravel
3. Renomeie o arquivo `.env.example` para `.env` e configure a conexão do banco de dados
4. Execute o comando `php artisan key:generate` para gerar uma chave para a aplicação
5. Execute o comando `php artisan migrate` para criar as tabelas no banco de dados
6. Execute o comando `php artisan db:seed` para popular o banco de dados com alguns registros de exemplo
7. Execute o comando `php artisan serve` para iniciar o servidor local

## 🦾 Uso
1. Abra o Postman ou outra ferramenta similar para testar a API
2. Crie um novo usuário fazendo uma requisição POST para `/api/register` com os seguintes parâmetros no corpo da requisição: name, email e password
3. Faça login na API fazendo uma requisição POST para `/api/login` com os seguintes parâmetros no corpo da requisição: email e password
4. Utilize o token gerado no login nas requisições para as rotas protegidas (index, show, store, update, delete) no header `Authorization: Bearer {token}`
5. Execute as operações CRUD com as rotas `/api/users` utilizando os métodos HTTP `GET`, `POST`, `PUT` e `DELETE`

## API endpoints
1. `GET /api/users`: lista todos os usuários
2. `POST /api/users`: cria um novo usuário
3. `PUT /api/users/{id}`: atualiza um usuário específico
4. `DELETE /api/users/{id}`: exclui um usuário específico

# O que foi criado ↴

## [AuthController.php](https://github.com/gabriel61/crud_laravel/blob/master/app/Http/Controllers/AuthController.php)
- O controlador AuthController.php contém um método login que é responsável por autenticar um usuário e gerar um token de acesso. 
- Ele recebe as credenciais do usuário (email e senha) na requisição POST e retorna um JSON com o token de acesso e informações do usuário. 
- O token é gerado utilizando o método `createToken` da classe `User` e é retornado no formato `Bearer {token}` no header da resposta.

```php
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
```

## [UserController.php](https://github.com/gabriel61/crud_laravel/blob/master/app/Http/Controllers/UserController.php)
- Gerencia as operações relacionadas ao usuário da aplicação. 
- Cada função do controlador é responsável por realizar uma operação específica, como listar todos os usuários, criar um novo usuário, atualizar um usuário existente ou excluir um usuário.

### Index
- Lista todos os usuários da tabela users e retorna um objeto JSON contendo os usuários.
```php
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }
```

### Create
- Este método cria um novo usuário na tabela users. 
- Ele recebe os dados do novo usuário através de uma requisição HTTP `POST` e valida se os campos name, email e password estão preenchidos corretamente. 
- Se estiverem, o usuário é criado e um objeto JSON contendo uma mensagem de sucesso e o usuário criado é retornado.
```php
public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = new User;
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $user
        ]);
    }
```
- A linha `$user->password = Hash::make($validatedData['password']);` é responsável por gerar o hash da senha informada pelo usuário antes de salvar no banco de dados. - A função `Hash::make()` é fornecida pelo Laravel e utiliza um algoritmo de hash forte e aleatório para criar uma string segura e irreversível.

### Show
- Este método retorna um usuário específico da tabela users. 
- Ele recebe o id do usuário como parâmetro através de uma requisição HTTP `GET` e busca o usuário correspondente no banco de dados. 
- Se encontrar, retorna um objeto JSON contendo o usuário. Se não encontrar, retorna uma mensagem de erro e um código `HTTP 404`.
```php
    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }
```

### Update
- Este método atualiza um usuário específico na tabela users. 
- Ele recebe o id do usuário como parâmetro através de uma requisição HTTP `PUT` e busca o usuário correspondente no banco de dados. 
- Se encontrar, valida os campos name, email e password da requisição e atualiza as informações do usuário. 
- Se o campo password estiver vazio, ele não é atualizado. 
- Retorna um objeto JSON contendo uma mensagem de sucesso e o usuário atualizado. 
```php
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $validatedData = $request->validate([
                'name' => 'string',
                'email' => 'email|unique:users,email,'.$id,
                'password' => 'string'
            ]);

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            if ($validatedData['password']) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            return response()->json([
                'message' => 'Usuário atualizado com sucesso!',
                'user' => $user
            ]);
        } else {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }
```

### Destroy
- Este método exclui um usuário específico da tabela users. 
- Ele recebe o id do usuário como parâmetro através de uma requisição HTTP `DELETE` e busca o usuário correspondente no banco de dados. 
- Se encontrar, o usuário é excluído e uma mensagem de sucesso é retornada em um objeto JSON.
```php
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();

            return response()->json([
                'message' => 'Usuário excluído com sucesso!'
            ]);
        } else {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }
```

## Rotas

### [Api.php](https://github.com/gabriel61/crud_laravel/blob/master/routes/api.php)
- A primeira rota define o endpoint `/login` para o método `login` do `AuthController` através do método `post()`.
- A segunda rota define o endpoint `/users` para o método `create` do `UserController` através do método `post()`. Essa rota não utiliza middleware de autenticação, pois é responsável por criar novos usuários.
- O middleware `auth:sanctum` é utilizado para proteger as rotas que seguem. Esse middleware exige que o usuário esteja autenticado para acessar essas rotas.
- As rotas dentro do grupo protegido por middleware definem os endpoints `/users`, `/users/{id}`, `/users/{id}` e `/users/{id}` respectivamente para os métodos `index`, `show`, `update` e `destroy` do `UserController`. Essas rotas são acessíveis apenas para usuários autenticados.

```php
Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'create']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
```
## Models

### [User.php](https://github.com/gabriel61/crud_laravel/blob/master/app/Models/User.php)
- Este é o modelo para a tabela de usuários do banco de dados. 
- O modelo contém propriedades que definem:
1. Quais atributos do usuário podem ser atribuídos em massa (definidos como `fillable`),
2. Quais atributos devem ser ocultos quando o usuário é serializado (definidos como `hidden`) e
3. Quais atributos devem ser convertidos em tipos de dados específicos (definidos como `casts`).

```php
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
```

---

### ✒️ Autor

</br>

<a href="https://github.com/gabriel61">
 <img style="border-radius: 50%;" src="https://avatars.githubusercontent.com/gabriel61" width="100px;" alt=""/>
 <br />
 
 [![Linkedin Badge](https://img.shields.io/badge/-gabrielsampaio-blue?style=flat-square&logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/gabriel-oliveira-852759190/)](https://www.linkedin.com/in/gabriel-oliveira-852759190/)
<br>
sogabris@gmail.com
<br>

---
