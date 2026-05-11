<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function login(Request $request)
    {
        // 1. Valida se o app enviou email e senha
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'string' // Opcional: pra saber se o login foi de um iPhone, Android, etc.
        ]);

        // 2. Busca o usuário no banco
        $user = User::where('email', $request->email)->first();

        // 3. Checa se o usuário existe e se a senha está correta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'As credenciais estão incorretas.'
            ], 401); // 401 = Unauthorized
        }

        // 4. Cria o Token! (Opcionalmente usando o nome do dispositivo)
        $tokenName = $request->device_name ?? 'finbot-app';
        $token = $user->createToken($tokenName)->plainTextToken;

        // 5. Devolve o Token e os dados do usuário pro React Native
        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        // Pega o token atual que fez a requisição e o deleta do banco
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso. Token revogado.'
        ], 200);
    }
}
