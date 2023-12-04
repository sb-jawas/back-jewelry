<?php

namespace App\Http\Controllers;

use App\Models\Componentes;
use App\Models\Lote;
use App\Models\LoteDespiece;
use App\Models\LoteUser;
use App\Models\StatusCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\HasPermission;

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
    public function store(Request $req, $userId)
    {
        
        $loteUser = new LoteUser();
        $loteUser->user_id = $userId;
        $loteUser->lote_id = $req->get("lote_id");

        $lote = $req->get("lote_id");
        
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
            if(empty($errors["lote_id"])){
                foreach ( $errors as $key => $value) {
                    $last = $key[strlen($key)-1];
                    $errorMessage[] = ["id_".$req->get("lote_id")[$last] =>" ya está asignado"];
                }
            }else{
                $errorMessage[] = ["required"=>"Es necesario selecionar al menos un lote"] ;
            }
        
            return response()->json(["msg"=>$errorMessage], 422);
        }

        if(count($req->get("lote_id"))==1){
            $this->saveLote($req,0, $userId);
        }else{
            $i = 0;
            while ($i < count($req->get("lote_id"))) {
                $this->saveLote($req,$i, $userId);
                $i++;
            }
        }
        $lote = Lote::find($req->get("lote_id"));
        foreach ($lote as $key ) {
            $key->status_code_id=4;
            $key->save();
        }
        return response()->json([ "msg" => "Asignado correctamente!", "store" => $loteUser]);
        
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

    public function infoDespiece($id){

        $lote = Lote::find($id);
        $idDespice = LoteDespiece::where('lote_id',$id)->get();
        $arrDes = [];
        $componentes = Componentes::all() ;
        $i = 0;
        while($i<count($idDespice)){
            $despiece = LoteDespiece::find($idDespice[$i]->id);
            $user = User::find($despiece->clasificador_id);

            $arrDes[] = [
                "cantidad"=>$despiece->cantidad,
                "componente"=>$componentes[$despiece->componente_id]->name,
                "observation"=>$despiece->observation,
                "clasificador_id"=>$user->name
            ];
            $i++;
        }

        return response()->json($arrDes);
        
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

    private function saveLote($req, $i, $userId) {
        $loteUser = new LoteUser;
        $loteUser->user_id = $userId;
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
