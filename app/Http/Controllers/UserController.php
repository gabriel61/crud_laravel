<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Lista todos os usuários da tabela users e retorna um objeto JSON contendo os usuários.
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }


    /**
     * Este método cria um novo usuário na tabela users.
     * Ele recebe os dados do novo usuário através de uma requisição HTTP POST e valida se os campos name, email e password estão preenchidos corretamente.
     * Se estiverem, o usuário é criado e um objeto JSON contendo uma mensagem de sucesso e o usuário criado é retornado.
     */
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

    /**
     * Este método retorna um usuário específico da tabela users.
     * Ele recebe o id do usuário como parâmetro através de uma requisição HTTP GET e busca o usuário correspondente no banco de dados.
     * Se encontrar, retorna um objeto JSON contendo o usuário. Se não encontrar, retorna uma mensagem de erro e um código HTTP 404.
     */
    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }

    /**
     * Este método atualiza um usuário específico na tabela users.
     * Ele recebe o id do usuário como parâmetro através de uma requisição HTTP PUT e busca o usuário correspondente no banco de dados.
     * Se encontrar, valida os campos name, email e password da requisição e atualiza as informações do usuário.
     * Se o campo password estiver vazio, ele não é atualizado.
     * Retorna um objeto JSON contendo uma mensagem de sucesso e o usuário atualizado.
     * Se não encontrar o usuário, retorna uma mensagem de erro e um código HTTP 404.
     */
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

    /**
     * Este método exclui um usuário específico da tabela users.
     * Ele recebe o id do usuário como parâmetro através de uma requisição HTTP DELETE e busca o usuário correspondente no banco de dados.
     * Se encontrar, o usuário é excluído e uma mensagem de sucesso é retornada em um objeto JSON.
     * Se não encontrar o usuário, retorna uma mensagem de erro e um código HTTP 404.
     */
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
}
