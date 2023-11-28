<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\LoteUser;
use Illuminate\Http\Request;

class LoteUserController extends Controller
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
        $loteUser = new LoteUser;
        $loteUser->user_id = $req->get("user_id");
        $loteUser->lote_id = $req->get("lote_id");
        if(count($req->get("lote_id"))==1){
            $loteUser = new LoteUser;
            $loteUser->user_id = $req->get("user_id");
            $loteUser->lote_id = $req->get("lote_id")[0];
            $loteUser->save();
            dd($loteUser);
        }else{
            $i = 0;
            while ($i < count($req->get("lote_id"))) {
                $loteUser = new LoteUser;
                $loteUser->user_id = $req->get("user_id");
                $loteUser->lote_id = $req->get("lote_id")[$i];
                $loteUser->save();
                $i++;
            }
        }
        $lote = Lote::find($req->get("lote_id"));
        foreach ($lote as $key ) {
            $key->status_code_id=2;
            $key->save();
        }
        return response()->json([ "msg" => "Asignado correctamente!", "store" => $loteUser]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
