<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    static function cargarImagen($request, $whereStorage){

        $messages = [
            "image" =>[  
                'required' => 'Es obligatorio la imagen',
                'image' => 'No tiene formato imagen',
                'mimes' => 'Mimes acpetados: jpeg,png,jpg,gif',
                'max' => 'El campo se excede del tamaño máximo'
            ],
        ];

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);
        if ($validator->fails()){
            return ["msg" => false, "errors"=>$validator->errors()];
        }
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store($whereStorage, 's3');
            $url = Storage::disk('s3')->url($path);
            $vec = [ "msg" =>true, "path" => $path, "url"=> $url];
            return $vec;
        }

        return "No se recibió ningún archivo";

    }
}
