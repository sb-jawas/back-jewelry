<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\LoteUser;
use App\Models\StatusCode;
use App\Models\User;
use Illuminate\Http\Request;

class ClasificadorController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $loteUser = new LoteUser();
        $loteUser->user_id = $req->get("user_id");
        $loteUser->lote_id = $req->get("lote_id");
        
        $searchLoteUser = \DB::select("select * from lote_has_user where user_id = ? and lote_id = ? ",[ $req->get("user_id"), $req->get("lote_id")[0]]) ;
        // if(count($req->get("lote_id"))==1){
        // }else{

        // }

        if($searchLoteUser==null){
            if(count($req->get("lote_id"))==1){
                $this->saveLote($req,0);
            }else{
                $i = 0;
                while ($i < count($req->get("lote_id"))) {
                    $this->saveLote($req,$i);
                    $i++;
                }
            }
            $lote = Lote::find($req->get("lote_id"));
            foreach ($lote as $key ) {
                $key->status_code_id=2;
                $key->save();
            }
            return response()->json([ "msg" => "Asignado correctamente!", "store" => $loteUser]);
        }else{
            return response()->json([ "msg" => "El lote ya está asignado", "store" => $loteUser],401);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getlote = Lote::where('user_id',$id)->get();
        
        $lote= Lote::find($getlote[0]->id);

        if($lote!=null){

            $status = StatusCode::find($lote->status_code_id);
            $user = User::find($lote->user_id);
            
            $rtnObj = $this->rtnShowLote($lote, $user, $status);
            $statusCode = 200;
        }else{
            $rtnObj = ["msg"=>"No tienes lotes"];
            $statusCode = 404;
        }
        return response()->json([$rtnObj], $statusCode);
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

    private function saveLote($req, $i) {
        $loteUser = new LoteUser;
        $loteUser->user_id = $req->get("user_id");
        $loteUser->lote_id = $req->get("lote_id")[$i];
        $loteUser->save();
    }

    private function rtnShowLote($lote, $user, $status){
        return  [
            "id"=> $lote->id,
            "user_id"=> $user->id,
            "empresa"=> $user->name,
            "ubi"=> $lote->ubi,
            "observation"=> $lote->observation,
            "status"=> $status->desc,
            "created_at"=>$lote->created_at
            ] ;
    }
}
