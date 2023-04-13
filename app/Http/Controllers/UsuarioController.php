<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Managers\DatabaseManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuarioController extends Controller
{
    // Página do usuário
    public function index()
    {
        return view('index');
    }

    // Página de estatísticas
    public function statistics()
    {
        return view('statistics');
    }

    // Autenticando login e criando sessão
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $usuario = Auth::user()->name;
            session(['usuario' => $usuario]);
            return response()->json(['erro' => false]);
        }
        return response()->json(['erro' => true]);
    }

    // Função de registro e criptografia
    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);

        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return response()->json(['erro' => true]);
        }

        try {
            $user = \App\Models\User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'password' => Hash::make($data['password']),
            ]);
            return response()->json(['erro' => false]);
        } catch (\Exception $e) {
            return response()->json(['erro' => true]);
        }
    }

    // Fechando sessão
    public function logout(Request $request)
    {
        $request->session()->forget('usuario');
        Auth::logout();
        return response()->json(['erro' => false]);
    }
}
