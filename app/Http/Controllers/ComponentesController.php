<?php

namespace App\Http\Controllers;

use App\Models\Componentes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @author: badr => @bhamidou
 */

class ComponentesController extends Controller
{
    /**
     * Mostrar todos los componentes en función del usuario.
     */
    public function index(String $userId)
    {
        $vecValidator = [
            "user_id" => $userId
        ];

        $message = [
            "user_id" => [
                "exists" => "Este usuario no existe",
            ]
        ];

        $validator = Validator::make($vecValidator, [
            'user_id' => 'exists:users,id',
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(),404);
        }

        $componentes = DB::select('select * from componentes where created_user_id = ?', [$userId]);
        return response()->json($componentes);
    }

    public function allComponentes(){
        $componentes = Componentes::all();
        return response()->json($componentes);
    }

    /**
     * Crear un nuevo componente.
     */
    public function store(Request $req)
    {
        $message =[
            'created_user_id' =>[
                "required" => "Es necesario el usuario",
                "exists" => "Este usuario no existe"
            ],
            'name' => [
                "required" => "Es necesario un nombre",
                "min" => "Como mínimo se permite un solo caracter",
                "max" => "Como máximo solo se permite 30 caracteres",

            ],
            'desc' => [
                "required" => "Es necesaria una descripción",
                "min" => "Como mínimo se permite un solo caracter",
                "max" => "Como máximo solo se permite 255 caracteres",
            ],
            'is_hardware' => [
                "required" => "Es necesario indicar si es de tipo hardware o no",
                "integer" => "Tiene que ser un número entero",
                "between" => "Solo se permite 0 ó 1",
            ],
        ];
        $validator = Validator::make($req->all(), [
            'created_user_id' => 'required|exists:users,id',
            'name' => 'required|min:1|max:30',
            'desc' => 'required|min:1|max:255',
            'is_hardware' => 'required|integer|between:0,1',

        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(),404);
        }

        $componente = Componentes::create($req->all());
        return response()->json($componente);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
