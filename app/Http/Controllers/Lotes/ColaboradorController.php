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

    public function show(String $userId,String $loteId){
        $vecValidator = [
            "user_id"=>$userId,
            "lote_id"=>$loteId
        ];
        $messages = [
            'user_id' => [
                'exists' => 'Este usuario no existe'
            ],
            'lote_id' => [
                'exists' => 'Este lote no existe'
            ],
        ];
        $validator = Validator::make($vecValidator, [
            'user_id' => 'exists:users,id',
            'lote_id' => 'exists:lote,id',

        ], $messages);

        if($validator->fails()){
            return response()->json($validator->errors(),404);
        }

        $showLote = DB::select('select lote.id, lote.lat, lote.long, lote.observation, lote.status_code_id as "status_code", status_code.name as "status",lote.created_at from lote inner join status_code on status_code.id = lote.status_code_id where lote.id = ? and user_id = ?',[$loteId, $userId]);
        $statusCode = 200;
        if($showLote == null){
            $statusCode = 404;
        };
        return response()->json($showLote, $statusCode);
    }

    public function store(Request $req){
        $messages = [
            'user_id' => [
                'required' => 'Es obligatorio el usuario'
            ],
            'ubi' => [
                'required' => 'Es obligatorio la ubicación'
            ],
            'observation' => [
                'required' => 'Es obligatorio la observación'
            ],
        ];
        $validator = Validator::make($req->all(), [
            'ubi' => 'required',
            'observation' => 'required',
            'user_id'  => 'required|exists:users,id'

        ], $messages);

        if($validator->fails()){
            return response()->json($validator->errors(),202);
        }

        $newLote = LoteController::store($req);

        return response()->json($newLote);
    }

    public function cancelar(Request $req, String $loteId){

        $vecValidator = [
            "user_id"=> $req->get("user_id"),
            "lote_id"=> $loteId
        ];

        $messages = [
                "user_id" => [
                    "required"=>"Es necesario un usuario",
                    "exists"=>"Este usuario no existe",
                ],
                "lote_id" => [
                    "required"=>"Es necesario el id del lote",
                    "exists"=>"Este lote no exite",
                ]
        ];

        $validator = Validator::make($vecValidator, [
            'user_id' => 'required|exists:users,id',
            'lote_id' => 'required|exists:lote,id',
        ], $messages);

        if($validator->fails() ){
            return response()->json($validator->errors(), 400);
        }
        $lote = Lote::find($loteId);
        
        $check = true;
        if($lote->user_id == $req->get("user_id")){
            $check = false;
        }
        if ($check) {
            $rtnObj = ["msg"=>"No es posible cancelar un lote que no ha creado"];

            return response()->json($rtnObj, 404);
        }

        $lote->status_code_id = 7;

        $lote->save();

        return response()->json($lote, 200);
    }
}
