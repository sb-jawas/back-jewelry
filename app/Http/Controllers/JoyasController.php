<?php

namespace App\Http\Controllers;

use App\Models\Joya;
use Illuminate\Http\Request;

/**
 * @author: badr => @bhamidou
 */

class JoyasController extends Controller
{
    /**
     * Para mostrar todas las joyas
     */
    public function index()
    {
        $joyas = Joya::all();
        return response()->json($joyas, 200);
    }

    /**
     * Crear una nueva joya
     */
    public function store(Request $request)
    {
        $newJoya = Joya::create($request->all());
        $newJoya->save();

        return response()->json($newJoya, 201);
    }

    /**
     * Mostrar una joya
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Eliminar joya
     */
    public function destroy(string $id)
    {
        //
    }
}
