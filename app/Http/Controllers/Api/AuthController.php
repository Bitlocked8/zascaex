<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Listar todos los usuarios
     */
    public function index()
    {
        // Trae todos los usuarios con su relación cliente
        $users = User::with('cliente')->get();

        return response()->json($users);
    }

    /**
     * Mostrar un usuario específico
     */
    public function show($id)
    {
        $user = User::with('cliente')->findOrFail($id);
        return response()->json($user);
    }
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    $user = Auth::user();

    // Solo clientes
    if ($user->rol_id != 5) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}
}
