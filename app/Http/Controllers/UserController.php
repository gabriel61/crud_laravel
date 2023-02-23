<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }


    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
