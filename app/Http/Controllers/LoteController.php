<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\LoteUser;
use App\Models\StatusCode;
use App\Models\User;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lotes = Lote::all();
        
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
            "status_code_id"=>$status->status_code_id,
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

    public function asignlote(Request $req){
        $loteUser = new LoteUser;
        $loteUser->user_id = $req->get("user_id");
        $loteUser->lote_id = $req->get("lote_id") ;
        $loteUser->save();
        $lote = Lote::find($req->get("lote_id"));
        $lote->status_code_id=2;
        $lote->save();
        return response()->json([$loteUser,$lote]);
    }
}
