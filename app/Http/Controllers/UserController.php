<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\StatusCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'name_empresa' => $request->name_empresa,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'start_at' => now(),
        ]);

        $success['token'] = $user->createToken('hola')->plainTextToken;

        return response()->json(["succes" => true, "data" =>$success, "message" => "user successfully registered!"] ); 
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
