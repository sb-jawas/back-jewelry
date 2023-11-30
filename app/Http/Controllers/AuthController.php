<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales no válidas'], 401);
        }
        $success['token'] = $user->createToken('hola')->plainTextToken;
        return response()->json($success);
    }

    public function register(Request $request)
{
    // Validar los datos recibidos
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'name_empresa' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    // Crear el usuario
    $user = User::create([
        'name' => $validatedData['name'],
        'name_empresa' => $validatedData['name_empresa'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']), // Encriptar la contraseña
        'start_at' => now(),
    ]);

    // Respuesta con los datos del usuario
    return response()->json(['user' => $user], 201);
}



}
