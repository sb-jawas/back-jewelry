<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\LoteDespiece;
use App\Models\LoteUser;
use App\Models\StatusCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req, $userId)
    {
        $lotes = \DB::select("SELECT lote.id, lote.ubi, lote.observation, lote.user_id, lote.status_code_id, lote.created_at FROM lote JOIN lote_has_user ON lote.id = lote_has_user.lote_id where lote_has_user.user_id = ? and status_code_id = 2 GROUP by id",[$userId]);
        // dd($lotes);
        $rtnMsg = [];
        $i = 0;
        
        while($i<count($lotes)){

            $status = StatusCode::find($lotes[$i]->status_code_id);
            $user = User::find($lotes[$i]->user_id);

            $arr = [
            "id"=>$lotes[$i]->id,
            "ubi"=>$lotes[$i]->ubi,
            "observation"=>$lotes[$i]->observation,
            "empresa"=>$user->name_empresa,
            "status"=>$status->name,
            "status_code_id"=>$status->id,
            "created_at"=>$lotes[$i]->created_at];

            $rtnMsg[] = $arr;
            $i++;
        }


        return response()->json($rtnMsg);
    }
    public function disponible()
    {
        $lotes = Lote::where('status_code_id',1)->get();
        
        $rtnMsg = [];
        $i = 0;
        
        while($i<count($lotes)){

            $status = StatusCode::find($lotes[$i]->status_code_id);
            $user = User::find($lotes[$i]->user_id);

            $arr = [
            "id"=>$lotes[$i]->id,
            "ubi"=>$lotes[$i]->ubi,
            "observation"=>$lotes[$i]->observation,
            "empresa"=>$user->name_empresa,
            "status"=>$status->name,
            "status_code_id"=>$status->id,
            "created_at"=>$lotes[$i]->created_at];

            $rtnMsg[] = $arr;
            $i++;
        }


        return response()->json($rtnMsg);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $lote = new Lote;
        $lote->ubi=$req->get("ubi");
        $lote->observation=$req->get("observation");
        $lote->user_id=$req->get("user_id");
        $lote->status_code_id=1;
        $lote->save();

        $lote->status = StatusCode::find($lote->status_code_id)->desc;

        return response()->json($lote);
    }

    /**
     * Display the specified resource.
     */
    public function show($loteId)
    {
        // dd("llego");
        $lote = Lote::find($loteId);
        $status = StatusCode::find($lote->status_code_id);
        $user = User::find($lote->user_id);

        $rtnObj = [
            "id"=> $lote->id,
            "user_id"=> $user->id,
            "user_name"=> $user->name,
            "ubi"=> $lote->ubi,
            "observation"=> $lote->observation,
            "status"=> $status->desc,
            ] ;
        return response()->json($rtnObj);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function clasficado(Request $req){
        $arrIds = $req->get("id");
        $arrCantidad =  $req->get("cantidad");
        $loteId =  $req->get("lote_id");
        $clasificador = $req->get("user_id");


        $i = 0;
        while($i<count($arrIds)){
            $despiece = new LoteDespiece;
            $despiece->cantidad =$arrCantidad[$i] ;
            $despiece->clasificador_id = $clasificador ;
            $despiece->lote_id = $loteId;
            $despiece->componente_id = $arrIds[$i] ;
            $despiece->save();
            $i++;
        }
        $rtnMsg = ["message"=>"Lote clasificado correctamente","despiece"=>$despiece];
        return response()->json($rtnMsg);
    }
}
