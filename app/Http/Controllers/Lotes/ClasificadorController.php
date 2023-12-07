<?php

namespace App\Http\Controllers\Lotes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Componentes;
use App\Models\Inventario;
use App\Models\Lote;
use App\Models\LoteDespiece;
use App\Models\LoteUser;
use App\Models\StatusCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * @author: badr => @bhamidou
 */

class ClasificadorController extends Controller
{
    /**
     * Para obtener los lotes pendientes de clasificar.
     */
    public function index(String $userId)
    {
        $vec = ["user_id"  => $userId];
        $validator = Validator::make($vec, [
            'user_id' => 'required|exists:lote_has_user,user_id',
        ]);

        if ($validator->fails()) {
            $rtnObj = ["msg" => "No tienes lotes"];

            return response()->json($rtnObj, 404);
        }

        $lotes = DB::select('select lote.id, users.name_empresa, lote.created_at from lote_has_user inner join lote on lote.id = lote_has_user.lote_id inner join users on lote.user_id = users.id where lote_has_user.user_id = ? and lote.status_code_id = 4', [$userId]);

        return response()->json($lotes);
    }

    /**
     * Para obtener la lista de lotes disponibles para asignarse.
     */

    public function disponible()
    {
        $lotes = DB::select('select lote.id, users.name_empresa as "empresa", lote.created_at, status_code.name as "status" from lote inner join users on users.id = lote.user_id inner join status_code on status_code.id = lote.status_code_id where lote.status_code_id = 3');

        return response()->json($lotes);
    }


    /**
     * Asignarse un lote.
     */
    public function store(Request $req, $userId)
    {

        $validator = Validator::make($req->all(), [
            'lote_id' => 'required',
            'lote_id.*' => [
                Rule::unique('lote_has_user', 'lote_id')->where(function ($query) use ($req) {
                    $query->whereIn('lote_id', $req->input('lote_id'));
                }),
            ],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->messages();
            if (empty($errors["lote_id"])) {
                foreach ($errors as $key) {
                    $last = $key[strlen($key) - 1];
                    $errorMessage[] = ["id_" . $req->get("lote_id")[$last] => " ya está asignado"];
                }
            } else {
                $errorMessage[] = ["required" => "Es necesario selecionar al menos un lote"];
            }

            return response()->json(["msg" => $errorMessage], 422);
        }

        $loteUser = new LoteUser();
        $loteUser->user_id = $userId;
        $loteUser->lote_id = $req->get("lote_id");

        $lote = $req->get("lote_id");

        if (count($req->get("lote_id")) == 1) {
            $this->saveLote($req->get("lote_id")[0], $userId);
        } else {
            $i = 0;
            while ($i < count($req->get("lote_id"))) {
                $this->saveLote($req->get("lote_id")[$i], $userId);
                $i++;
            }
        }
        $lote = Lote::find($req->get("lote_id"));
        foreach ($lote as $key) {
            $key->status_code_id = 4;
            $key->save();
        }
        return response()->json(["msg" => "Asignado correctamente!", "store" => $loteUser]);
    }

    /**
     * Mostrar un lote
     */
    public function show(string $loteId)
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
            return response()->json($validator->errors(),404);
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
        return response()->json($lote,200);
    }

    /**
     * Mostrar el despiece de un lote.
     */

    public function infoDespiece(String $loteId)
    {
        $vec = ["lote_id"  => $loteId];

        $message = [
            "lote_id" => [
                "exists" => "Este lote no existe",
            ]
        ];

        $validator = Validator::make($vec, [
            'lote_id' => 'required|exists:componentes_has_lote,lote_id',
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(),404);
        }


        $despiece = DB::select('select componentes_has_lote.cantidad as cantidad, componentes.name as componente, componentes_has_lote.observation, (select name from users where id = componentes_has_lote.clasificador_id) as "clasificador_id" from componentes_has_lote inner join lote on componentes_has_lote.lote_id = lote.id  inner join componentes on componentes_has_lote.componente_id = componentes.id  inner join users on users.id = lote.user_id where lote_id = ?', [$loteId]);
        

        return response()->json($despiece, 200);
    }

    private function saveLote($loteId, $userId)
    {
        $loteUser = new LoteUser;
        $loteUser->user_id = $userId;
        $loteUser->lote_id = $loteId;
        $loteUser->save();
    }

    public function clasificar(Request $req, $loteId)
    {
        $arrIds = $req->get("id");
        $arrCantidad =  $req->get("cantidad");
        $clasificador = $req->get("user_id");
        $obs = $req->get("observation");


        $i = 0;
        while ($i < count($arrIds)) {
            $despiece = new LoteDespiece;
            $despiece->cantidad = $arrCantidad[$i];
            $despiece->clasificador_id = $clasificador;
            $despiece->lote_id = $loteId;
            $despiece->componente_id = $arrIds[$i];
            $despiece->observation = $obs[$i];
            $despiece->save();

            $inventario = DB::select('select id from inventario where componente_id = ? ',[$despiece->componente_id]);

            if($inventario == null){
                $newInventario = new Inventario;
                $newInventario->cantidad = $despiece->cantidad;
                $newInventario->componente_id = $despiece->componente_id;
                $newInventario->save();
            }else{
                $updateInventario = Inventario::find($inventario[0]->id);
                $updateInventario->cantidad += $despiece->cantidad;
                $updateInventario->save();
            }
            
            $i++;
        }
        
        $lote = Lote::find($loteId);
        $lote->status_code_id = 5;
        $lote->save();

        $rtnMsg = ["message" => "Lote clasificado correctamente", "despiece" => $despiece];
        return response()->json($rtnMsg);
    }

    public function rechazar(String $loteId)
    {
        $lote = Lote::find($loteId);
        $lote->status_code_id = 6;
        $lote->save();
        return response()->json($lote);
    }
}
