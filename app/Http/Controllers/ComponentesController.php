<?php

namespace App\Http\Controllers;

use App\Models\Componentes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @author: badr => @bhamidou
 */

class ComponentesController extends Controller
{
    /**
     * Mostrar todos los componentes en función del usuario.
     */
    public function index(String $userId)
    {
        $vecValidator = [
            "user_id" => $userId
        ];

        $message = [
            "user_id" => [
                "exists" => "Este usuario no existe",
            ]
        ];

        $validator = Validator::make($vecValidator, [
            'user_id' => 'exists:users,id',
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $componentes = DB::select('select * from componentes where created_user_id = ?', [$userId]);
        return response()->json($componentes);
    }

    public function allComponentes()
    {
        $componentes = Componentes::all();
        return response()->json($componentes);
    }

    /**
     * Crear un nuevo componente.
     */
    public function store(Request $req)
    {
        $message = $this->validatorMessages();
        $validator = Validator::make($req->all(), [
            'created_user_id' => 'required|exists:users,id',
            'name' => 'required|min:1|max:30',
            'desc' => 'required|min:1|max:255',
            'is_hardware' => 'required|integer|between:0,1',

        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        try {
            $componente = Componentes::create($req->all());
            return response()->json($componente);
        } catch (\Exception $exception) {
            return response()->json(["msg" => $exception->getMessage()], 500);
        }
    }

    /**
     * Muestra un componente en específico.
     */
    public function show(string $componenteId)
    {

        $vecValidator = ["componente_id" => $componenteId];
        $message = [
            "componente_id" => [
                "required" => "Es necesario el ID componente",
                "exists" => "Este componente no existe",
            ]
        ];
        $validator = Validator::make($vecValidator, [
            'componente_id' => 'required|exists:componentes,id',
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $componente = Componentes::find($componenteId);
        return response()->json($componente);
    }

    public function showByUser(String $userId, String $componenteId)
    {

        $vecValidator = [
            "componente_id" => $componenteId,
            "user_id" => $userId
        ];
        $message = [
            "componente_id" => [
                "required" => "Es necesario el ID componente",
                "exists" => "Este componente no existe",
            ],
            "user_id" => [
                "required" => "Es necesario el ID del usuario"
            ]
        ];
        $validator = Validator::make($vecValidator, [
            'componente_id' => 'required|exists:componentes,id',
            'user_id' => 'required',
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $compByUser = DB::select('select id from componentes where created_user_id = ? and id = ?', [$userId, $componenteId]);

        if (!empty($compByUser[0])) {
            $componente = Componentes::find($componenteId);
            return response()->json($componente);
        } else {
            return response()->json(["msg" => "Este componente no te pertenece"], 404);
        }
    }

    /**
     * Actualiza un componente en especifico.
     */
    public function update(Request $req, string $componenteId)
    {
        $vecValidator = [
            "componente_id" => $componenteId,
            "name" => $req->get("name"),
            "desc" => $req->get("desc"),
            "is_hardware" => $req->get("is_hardware")

        ];
        $message = $this->validatorMessages();;
        $validator = Validator::make($vecValidator, [
            'componente_id' => 'exists:componentes,id',
            'name' => 'max:30',
            'desc' => 'max:255',
            'is_hardware' => 'between:0,1',
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $compByUser = DB::select('select id from componentes where created_user_id = ? and id = ?', [$req->get("user_id"), $componenteId]);
        $isAdmin = DB::select('select rol_id from componentes inner join rol_has_user on componentes.created_user_id = rol_has_user.user_id where created_user_id = ? or rol_id = 4', [$req->get("user_id")]);
        try {
            if (!empty($isAdmin) || !empty($compByUser[0])) {
                $componente = Componentes::where('id', $componenteId)->get();

                // $i = 0;
                // $vecDatos = ["name", "desc", "is_hardware"];
                // $t = $req->all();
                // $t["is_hardware"] = strval($t["is_hardware"]);
                // $size = count($t);
                // $arrVec = [];
                // while ($i < $size) {
                //     if ($t[$vecDatos[$i]] != null) {
                //         if ($vecDatos[$i] == "is_hardware") {
                //             $arrVec[$vecDatos[$i]] = intval($t[$vecDatos[$i]]);
                //         } else {
                //             $arrVec[$vecDatos[$i]] = $t[$vecDatos[$i]];
                //         }
                //     } else {
                //         $arrVec[$vecDatos[$i]] = $componente[0][$vecDatos[$i]];
                //     }
                //     $i++;
                // }
                $vecHard = [
                    "is_hardware" => $req->get('is_hardware')
                ];
                $vecDesc = [
                    "desc" => $req->get('desc')
                ];
                $vecName = [
                    "name" => $req->get('name')
                ];

                if (!empty($req->get('name'))) {
                    $componente = Componentes::find($componenteId)->update($vecName);
                }
                if (!empty($req->get('desc'))) {
                    $componente = Componentes::find($componenteId)->update($vecDesc);
                }

                if ($req->get('is_hardware') != null) {
                    $componente = Componentes::find($componenteId)->update($vecHard);
                }

                $componente = Componentes::find($componenteId);

                return response()->json($componente, 200);
            } else {
                return response()->json(["msg" => "Este componente no te pertenece"], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(["msg" => $exception->getMessage()], 500);
        }
    }


    /**
     * Elimina un componente de un usuario
     */
    public function destroy(Request $req, string $componenteId)
    {
        $vecValidator = [
            "componente_id" => $componenteId,
            "user_id" => $req->get("user_id")
        ];
        $message = [
            "componente_id" => [
                "required" => "Es necesario el ID componente",
                "exists" => "Este componente no existe",
            ],
            "user_id" => [
                "required" => "Es necesario el ID del usuario",
                "exists" => "Este usuario no existe",
            ]
        ];
        $validator = Validator::make($vecValidator, [
            'componente_id' => 'required|exists:componentes,id',
            'user_id' => 'required|exists:users,id',
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $compByUser = DB::select('select id from componentes where created_user_id = ? and id = ?', [$req->get("user_id"), $componenteId]);
        $isAdmin = DB::select('select rol_id from componentes inner join rol_has_user on componentes.created_user_id = rol_has_user.user_id where created_user_id = ? and rol_id = 4', [$req->get("user_id")]);
        try {
            if (!empty($isAdmin) || !empty($compByUser[0])) {
                Componentes::destroy($componenteId);
                return response()->json(["msg" => "Componente eliminado correctamente"], 200);
            } else {
                return response()->json(["msg" => "Este componente no te pertenece"], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(["msg" => $exception->getMessage()], 500);
        }
    }

    private function validatorMessages()
    {
        return [
            'created_user_id' => [
                "required" => "Es necesario el usuario",
                "exists" => "Este usuario no existe"
            ],
            'name' => [
                "required" => "Es necesario un nombre",
                "min" => "Como mínimo se permite un solo caracter",
                "max" => "Como máximo solo se permite 30 caracteres",

            ],
            'desc' => [
                "required" => "Es necesaria una descripción",
                "min" => "Como mínimo se permite un solo caracter",
                "max" => "Como máximo solo se permite 255 caracteres",
            ],
            'is_hardware' => [
                "required" => "Es necesario indicar si es de tipo hardware o no",
                "integer" => "Tiene que ser un número entero",
                "between" => "Solo se permite 0 ó 1",
            ],
        ];
    }
}
