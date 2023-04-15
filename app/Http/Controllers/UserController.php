<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
            $user = Auth::user()->name;
            session(['user' => $user]);
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
        $request->session()->forget('user');
        Auth::logout();
        return response()->json(['erro' => false]);
    }
}
