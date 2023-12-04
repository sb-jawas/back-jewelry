<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class CRUDController extends Controller
{
    public function userDelete($id)
    {
        // Aquí va tu lógica para borrar el usuario
        // Por ejemplo: User::destroy($id);

        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }

    public function getUsers()
    {
        $users = User::all(['name', 'email']); // Suponiendo que los campos 'name' y 'email' existen en tu modelo User
        return response()->json($users);
    }
}
