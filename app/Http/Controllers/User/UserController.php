<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\service\ImageController;
use App\Models\Lote;
use App\Models\Rol;
use App\Models\RolUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


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
            'name_empresa.required' => 'El nombre de empresa es obligatorio',
            'name_empresa.unique' => 'El nombre de la empresa ya está en uso',
            'start_at.date' => 'No es una fecha',
            'end_at.date' => 'No es una fecha',
            'end_at.after' => 'La fecha tiene que ser mayor a la de hoy',
        ];
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'name_empresa' => 'required|unique:users,name_empresa',
            'start_at' => 'date',
            'end_at' => 'date|after:today',
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors(), "status"=>400], 400);
        }
    
        $input = $request->all();
        $randPass = Str::random(12);
        $input['password'] = bcrypt($randPass);

        if(empty($request->get('start_at'))){
            $input['start_at'] = now();
        }

        $user = User::create($input);
        
        if(empty($request->get('roles'))){
            $vecUserRol = [
                "user_id" => $user->id,
                "rol_id" => 1
            ];
            RolUser::create($vecUserRol);
        }else{
            RolUser::create($request->get('roles'));
        }
        
        return response()->json(["msg" => "Usuario registrado", "status"=>200],200);
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
        $roles = DB::select('select rol.id,rol.name from rol inner join rol_has_user on rol.id = rol_has_user.rol_id where rol_has_user.user_id = ?',[$userId]);
        $user->roles = $roles;
        
        return response()->json(["msg" => $user,"status"=>200], 200);

    }

    /**
     * Actualizar un usuario.
     */
    public function update(Request $request, string $userId)
    {
        $messages = [
            'email.email' => 'El campo email debe ser una dirección de correo válida.',
            'email.unique' => 'El email ya está registrado.',
            'name_empresa.unique' => 'El nombre de la empresa ya está en uso'
        ];
    
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email',
            'name_empresa' => 'unique:users,name_empresa',
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors(), "status"=>400], 400);
        }

        $user = User::find($userId)->update($request->all());

        $user = User::find($userId);

        return response()->json($user, 200);
        
    }

    public function updateRoles(Request $request, String $userId){
        $messages = [
            '*.rol_id.required' => 'Es necesario el rol',
            '*.rol_id.exists' => 'Este rol no existe'
        ];
    
        $validator = Validator::make($request->all(), [
            '*.rol_id' => 'required|exists:rol,id',
        ], $messages);
    
        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors(), "status"=>400], 400);
        }

        $user = RolUser::where('user_id',$userId)->delete();
        $user = RolUser::insert($request->all());
        

        $user = User::find($userId);

        return response()->json($user, 200);
    }

    public function updateImage(Request $request, $userId){
        $image = ImageController::cargarImagen($request,"perfiles");
        if($image["msg"]){
            User::find($userId)->update(["profile"=>$image["url"]]);
            return response()->json($image, 200);
        }else{
            return response()->json($image["errors"], 400);
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy(string $userId){
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

        User::destroy($userId);
        return response()->json(["msg" => "Eliminado correctamente", "status"=>200], 200);

    }

    public function darBaja(string $userId){
        $user = $this->bajaUser($userId, now());
        if($user["msg"]){
            return response()->json($user, 200);
        }else{
            return response()->json($user["errors"], 400);
        }
    }

    public function programBaja(Request $req, String $userId){

        $messages = [
            'user_id' => [
                'exists' => 'Este usuario no existe'
            ]
        ];
    
        $validator = Validator::make($req->all(), [
            'end_at' => 'date|after:today',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(["msg" => $validator->errors(), "status"=>400], 400);
        }

        $user = $this->bajaUser($userId, $req->get("end_at"));
        if($user["msg"]){
            return response()->json($user, 200);
        }else{
            return response()->json($user["errors"], 400);
        }
    }


    public function bajaUser(String $userId, $date){
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
            return $validator->errors();
        }



        $user = User::find($userId);
        $user->end_at = $date;
        $user->tokens()->delete();
        $user->save();

        return ["msg" => "Usuario dado de baja correctamente", "status"=>200];
    }

    public function activeUser(String $userId){
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
        $user->end_at = null;
        
        $user->save();
    }

    public function searchUserByEmail(Request $req){
        $pattern = $req->get("email");
        $users = DB::table('users')->where('email', 'like', '%' . $pattern . '%')->get();

        if(count($users)>=1){
            return response()->json($users);
        }else{
            return response()->json("No se ha podido localizar este usuario",404);
        }
        
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

    public function uploadImage(Request $request){
        $image = ImageController::cargarImagen($request,"perfiles");
        if($image["msg"]){
            return response()->json($image, 200);
        }else{
            return response()->json($image["errors"], 400);
        }
    }
}
