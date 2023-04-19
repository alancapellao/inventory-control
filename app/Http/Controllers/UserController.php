<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        if (isset($credentials['email']) && !empty($credentials['email']) && isset($credentials['password']) && !empty($credentials['password'])) {

            if (auth()->attempt($credentials)) {
                $user = Auth::user()->name;
                session(['user' => $user]);

                return response()->json(['error' => false, 'message' => 'Login successful.']);
            }
            return response()->json(['error' => true, 'message' => 'Invalid email or password.']);
        }
        return response()->json(['error' => true, 'message' => 'Invalid data.']);
    }

    // Função de registro e criptografia
    public function register(Request $request)
    {
        $credentials = $request->only(['name', 'email', 'password']);

        if (isset($credentials['name']) && !empty($credentials['name']) && isset($credentials['email']) && !empty($credentials['email']) && isset($credentials['password']) && !empty($credentials['password'])) {

            try {
                $user = User::create([
                    'name' => $credentials['name'],
                    'email' => strtolower($credentials['email']),
                    'password' => Hash::make($credentials['password']),
                ]);

                return response()->json(['error' => false, 'message' => 'Successfully registered.']);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Email already exists.']);
            }
        }
        return response()->json(['error' => true, 'message' => 'Invalid data.']);
    }

    // Fechando sessão
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        Auth::logout();

        return response()->json(['error' => false, 'message' => 'Logout successfully.']);
    }
}
