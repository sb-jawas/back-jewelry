<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class CRUDController extends Controller
{
    public function userDelete($id)
    {
        // Encuentra el usuario por su ID y elimínalo
    $user = User::find($id);

    if ($user) {
        $user->delete();

        // Si la eliminación fue exitosa, retorna una respuesta JSON con un mensaje de éxito
        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    } else {
        // Si no se encuentra el usuario, retorna una respuesta JSON con un mensaje de error
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }
    }

    public function getUsers()
    {
        $users = User::all(['id','name', 'email','name_empresa']); // Suponiendo que los campos 'name' y 'email' existen en tu modelo User
        return response()->json($users);
    }
}
