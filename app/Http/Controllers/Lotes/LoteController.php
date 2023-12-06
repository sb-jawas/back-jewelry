<?php

namespace App\Http\Controllers\Lotes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Lote;
use App\Models\LoteDespiece;
use App\Models\LoteUser;
use App\Models\StatusCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId)
    {

        $vec = ["user_id"  => $userId];
        $validator = Validator::make($vec, [
            'user_id' => 'required|exists:users,id|exists:lote,user_id',
        ]);

        if ($validator->fails()) {
            $rtnObj = ["msg" => "No tienes lotes"];

            return response()->json($rtnObj, 404);
        }
        // $lotes = DB::select("SELECT lote.id, lote.ubi, lote.observation, lote.user_id, lote.status_code_id, lote.created_at FROM lote inner JOIN lote_has_user ON lote.id = lote_has_user.lote_id where lote_has_user.user_id = ? and status_code_id = 2 GROUP by id",[$userId]);
        $lotes = Lote::where("user_id", $userId)->get();
        // dd($lotes);

        $rtnMsg = [];
        $i = 0;

        while ($i < count($lotes)) {

            $status = StatusCode::find($lotes[$i]->status_code_id);
            $user = User::find($lotes[$i]->user_id);

            $arr = [
                "id" => $lotes[$i]->id,
                "lat" => $lotes[$i]->lat,
                "long" => $lotes[$i]->long,
                "observation" => $lotes[$i]->observation,
                "empresa" => $user->name_empresa,
                "status" => $status->name,
                "status_code_id" => $status->id,
                "created_at" => $lotes[$i]->created_at
            ];

            $rtnMsg[] = $arr;
            $i++;
        }


        return response()->json($rtnMsg);
    }

    /**
     * Store a newly created resource in storage.
     */
    static function store(Request $req)
    {
        $lote = new Lote;
        $lote->lat = $req->get("ubi")[0];
        $lote->long = $req->get("ubi")[1];
        $lote->observation = $req->get("observation");
        $lote->user_id = $req->get("user_id");
        $lote->status_code_id = 1;
        $lote->save();

        $lote->status = StatusCode::find($lote->status_code_id)->desc;

        return $lote;
    }

    /**
     * Display the specified resource.
     */
    static function show($loteId)
    {
        $vec = ["lote_id"  => $loteId];

        $message = [
            "lote_id" => [
                "required" => "Es necesario el ID lote",
                "exists" => "Este lote no existe",

            ]
        ];

        $validator = Validator::make($vec, [
            'lote_id' => 'required|exists:lote,id',
        ], $message);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $lote = Lote::find($loteId);
        $status = StatusCode::find($lote->status_code_id);
        $user = User::find($lote->user_id);

        $rtnObj = [
            "id" => $lote->id,
            "user_id" => $user->id,
            "user_name" => $user->name,
            "lat" => $lote->lat,
            "long" => $lote->long,
            "observation" => $lote->observation,
            "status" => $status->desc,
            "status_code" => $status->id,

        ];


        return $rtnObj;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $loteId)
    {
        $lote = Lote::find($loteId);
        return response()->json($lote);
    }

    /**
     * @author: Badr
     * Esta función servirá para cancelar el lote que crea una empresa.
     */

    public function cancelar(Request $req, $userId)
    {

        $vec = [
            "user_id"  => $userId,
            "lote_id" => $req->get("lote_id")
        ];

        $messages = [
            "user_id" => [
                "required" => "Es necesario un usuario",
                "exists" => "Este usuario no existe",
            ],
            "lote_id" => [
                "required" => "Es necesario el id del lote",
                "exists" => "Este lote no exite",
            ]
        ];

        $validator = Validator::make($vec, [
            'user_id' => 'required|exists:users,id',
            'lote_id' => 'required|exists:lote,id',
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $lote = Lote::find($req->get("lote_id"));

        $check = true;
        if ($lote->user_id == $userId) {
            $check = false;
        }
        if ($check) {
            $rtnObj = ["msg" => "No es posible cancelar un lote que no ha creado"];

            return response()->json($rtnObj, 404);
        }

        $lote->status_code_id = 7;
        // $lote->observation = "actualizado";
        $lote->save();

        return response()->json($lote, 200);
    }

}
