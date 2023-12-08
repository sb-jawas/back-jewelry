<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Rol;
use App\Models\RolUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


/**
 * @author: badr => @bhamidou
 */

class UserController extends Controller
{
    //Roles de un usuario.
    public function roles($userId){
        $user = RolUser::where('user_id',$userId)->get();
        
        $rtnMsg = [];
        $sizeRoles = count($user);
        
        for ($i=0; $i <  $sizeRoles ; $i++) {
            
            $rol = Rol::find($user[$i]->rol_id);

            array_push($rtnMsg, $rol);
        }
        $code = 202;
        
        return response()->json($rtnMsg,$code);
    }
    /**
     * Para mostrar todos los usuarios.
     */
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    /**
     * Para que un administrador cree un usuario.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'El campo name es obligatorio.',
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya está registrado.',
            'password.required' => 'La contraseña es obligatorio.',
            'name_empresa.required' => 'El nombre de empresa es obligatorio',
            'name_empresa.unique' => 'El nombre de la empresa ya está en uso',
            'start_at.required' => 'La fecha para dar de alta es obligatoria',
            'start_at.date' => 'No es una fecha',
            'end_at.required' => 'La fecha para dar de baja es obligatoria',
            'end_at.date' => 'No es una fecha',
            'end_at.after' => 'La fecha tiene que ser mayor a la de hoy',
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'name_empresa' => 'required|unique:users,name_empresa',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:today',
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors(), "status"=>400], 400);
        }
    
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        $vecUserRol = [
            "user_id" => $user->id,
            "rol_id" => 1
        ];

        $rolUser = RolUser::create($vecUserRol);

        return response()->json([ "user" => [$user, $rolUser], "msg" => "Usuario registrado", "status"=>200],200);
    }

    /**
     * Muestra la información de un usuario en concreto.
     */
    public function show(Request $req, string $userId)
    {
        $vecValidator = [
            "user_id" => $userId,
        ];
        $messages = [
            'user_id' => [
                'exists' => 'Este usuario no existe'
            ]
        ];
    
        $validator = Validator::make($vecValidator, [
            'user_id' => 'exists:users,id',
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors(), "status"=>400], 400);
        }
        
        $user = User::find($userId);
        return response()->json(["msg" => $user, "status"=>200], 200);

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

    public function userLote(string $userId, string $loteId){
        $user = User::find($userId);
        $lote = Lote::where('user_id',$userId)->where('id',$loteId)->get();
        return response()->json($lote);
    }

    public function userLotes(string $userId){
        $user = User::find($userId);
        $lote = Lote::where('user_id',$userId)->get();
        return response()->json($lote);
    }
}
