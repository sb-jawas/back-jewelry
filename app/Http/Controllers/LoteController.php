<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\LoteUser;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lotes = Lote::all();
        return response()->json($lotes);
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
        return response()->json($lote);
    }

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {
        $lotes = Lote::where('user_id',$userId)->get();
        return response()->json($lotes);
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
