<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RolUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];
                
        if(Auth::attempt($credentials)){
            $auth = Auth::user();
            if($auth->start_at <= now()){
                if($auth->end_at >=now() || $auth->end_at == null){

                $idUser = $auth->id;
                $vecRoles = DB::select('SELECT rol.name as "rol" FROM `rol_has_user` inner join rol on rol.id = rol_has_user.rol_id where  rol_has_user.user_id = ?', [$idUser]);
                $roles = [];
                $i = 0;
                while($i<count($vecRoles)){
                    $roles[] = $vecRoles[$i]->rol;
                    $i++;
                }
                    $success['token'] =  $auth->createToken('access_token',$roles)->plainTextToken;
                $success['user_id'] =  $auth->id;

                return response()->json($success, 200);
            }else{
                return response()->json(["msg"=>"Este usuario está de baja desde el ".$auth->end_at],400);
            }
            }else{
                return response()->json(["msg"=>"Este usuario todavía no puede iniciar sesión hasta el ".$auth->start_at],400);
            }
        }
        else{
            return response()->json("Unauthorized",401);
        }
    }
    public function signup(Request $request)
    {
        $messages = [
            'name.required' => 'El campo name es obligatorio.',
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya está registrado.',
            'password.required' => 'La contraseña es obligatorio.',
            'name_empresa.required' => 'El nombre de empresa es obligatorio',
            'name_empresa.unique' => 'El nombre de la empresa ya está en uso'
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'name_empresa' => 'required|unique:users,name_empresa'
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors(), "status"=>400], 400);
        }
    
        $input = $request->all();
        $input["start_at"] = now();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        $vecUserRol = [
            "user_id" => $user->id,
            "rol_id" => 1
        ];

        $rolUser = RolUser::create($vecUserRol);

        return response()->json([ "user" => [$user, $rolUser], "msg" => "Usuario registrado", "status"=>200],200);
    }

    public function logout(Request $request)
    {
        $cred = ['email' => $request->email, 'password' => $request->password];
        $token = strval($request->bearerToken());
        $check = true;
        $i = 0;
        $tokenId = "";
        while($check){
            if($token[$i]=="|"){
                $check = false;
            }else{
                $tokenId .= $token[$i];
            }
            $i++;
        }
        if(Auth::attempt($cred)){
            Auth::user()->tokens()->where('id',$tokenId)->delete();
            return response()->json(["msg"=>"Sesión actual cerrada"],200);
        }
        else {
            return response()->json("Unauthorized",401);
        }

    }

    public function fullLogout(Request $request){
        $cred = ['email' => $request->email, 'password' => $request->password];
                
        if(Auth::attempt($cred)){
            Auth::user()->tokens()->delete();
            return response()->json(["msg"=>"Se ha cerrado sesión en todos sus dispositivos."],200);
        }
        else {
            return response()->json("Unauthorized",401);
        }
    }
}
