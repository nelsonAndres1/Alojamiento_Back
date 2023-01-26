<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tiposaloja;
use App\Models\Taraloja;

class TiposalojaController extends Controller
{
    //getAll_tiposaloja

    public function getAll_tiposaloja(Request $request)
    {
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, []);
        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        } else {
            $signup = $jwtAuth->getAll_tiposaloja();
        }
        return response()->json($signup, 200);
    }
    public function register(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        $data = array(
            'status' => 'error',
            'code' => 404,
            'message' => 'Datos enviados no correctos'
        );
        //Limpiar los datos
        if (!empty($params) && !empty($params_array)) {
            $params_array = array_map('trim', $params_array); //Limpiar los datos 

            //validar datos
            $validate = Validator::make($params_array, [
                'detalle' => 'required',
                'estado' => 'required',
                'titulo' => 'required',
                'parrafo' => 'required',
                'disponibles' => 'required',
                'usuario' => 'required',
            ]);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No creado',
                    'errors' => $validate->errors()
                );
            } else {

                
                $hoy = date("Y-m-d");
                $tiposaloja = new Tiposaloja();
                $tiposaloja->detalle = $params_array['detalle'];
                $tiposaloja->estado = $params_array['estado'];
                $tiposaloja->titulo = $params_array['titulo'];
                $tiposaloja->parrafo = $params_array['parrafo'];
                $tiposaloja->disponibles = $params_array['disponibles'];
                $tiposaloja->usuario = $params_array['usuario'];
                $tiposaloja->fecha = $hoy;
                $tiposaloja->save();

                if ($tiposaloja) {
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'si creado',
                        'user' => $tiposaloja
                    );
                } else {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No creado',
                        'user' => $tiposaloja
                    );
                }
            }
        }

        return response()->json($data, $data['code']);
    }
}