<?php

namespace App\Http\Controllers\Lotes;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
/**
 * @author: Badr => @bhamidou
 */

class ColaboradorController extends Controller
{
    public function index(String $userId){

        $vecValidator = ["user_id"=>$userId];
        $messages = [
            'exists' => 'Este usuario no existe'
        ];
        $validator = Validator::make($vecValidator, [
            'user_id' => 'exists:users,id',

        ], $messages);

        if($validator->fails()){
            return response()->json($validator->errors(),202);
        }

        $lotesUser = DB::select('select lote.id, lote.status_code_id, lote.created_at, status_code.name from lote inner join status_code on status_code.id = lote.status_code_id where user_id = ?', [$userId]);

        return response()->json($lotesUser,200);
    }

    public function show(String $loteId){
       
        return response()->json("funciona");
    }

    public function store(){
        

        return response()->json("funca");
    }

    public function rechazar(String $loteId){
       

        return response()->json("funciona");
    }
}
