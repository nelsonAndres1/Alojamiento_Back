<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Taraloja;

class TaralojaController extends Controller
{
    public function register(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true); 
        $data = array(
            'status' => 'error',
            'code'   => 404,
            'message' => 'Datos enviados no correctos'      
        );       
        //Limpiar los datos
        if(!empty($params) && !empty($params_array)){
            $params_array = array_map('trim', $params_array);//Limpiar los datos 
            
            //validar datos
            $validate = Validator::make($params_array,[
                'tiposaloja_id' =>'required',
                'tarA' =>'required',
                'tarB' =>'required',
                'tarC' =>'required',
                'tarD' =>'required',
                'tarE' =>'required',
                'usuario'=>'required',
            ]);

            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code'   => 404,
                    'message' => 'No creado',
                    'errors' => $validate->errors()
                );
            }else{

                $hoy = date("Y-m-d");
                $taraloja_consulta = Taraloja::where('tiposaloja_id', $params_array['tiposaloja_id'])->get();

                if(count($taraloja_consulta)>0){
                    Taraloja::where('tiposaloja_id', $params_array['tiposaloja_id'])->delete();
                    $taraloja = new Taraloja();
                    $taraloja->tiposaloja_id = $params_array['tiposaloja_id'];
                    $taraloja->tarA = $params_array['tarA'];
                    $taraloja->tarB = $params_array['tarB'];
                    $taraloja->tarC = $params_array['tarC'];
                    $taraloja->tarD = $params_array['tarD'];
                    $taraloja->tarE = $params_array['tarE'];
                    $taraloja->usuario = $params_array['usuario'];
                    $taraloja->fecha = $hoy;
                    $taraloja->save();
    
                    if($taraloja){
                        $data = array(
                            'status' => 'success',
                            'code'   => 200,
                            'message' => 'si creado',
                            'user' => $taraloja
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => 400,
                            'message' => 'No creado',
                            'user' => $taraloja
                        );
                    }
                }else{
                    $taraloja = new Taraloja();
                    $taraloja->tiposaloja_id = $params_array['tiposaloja_id'];
                    $taraloja->tarA = $params_array['tarA'];
                    $taraloja->tarB = $params_array['tarB'];
                    $taraloja->tarC = $params_array['tarC'];
                    $taraloja->tarD = $params_array['tarD'];
                    $taraloja->tarE = $params_array['tarE'];
                    $taraloja->usuario = $params_array['usuario'];
                    $taraloja->fecha = $hoy;
                    $taraloja->save();
    
                    if($taraloja){
                        $data = array(
                            'status' => 'success',
                            'code'   => 200,
                            'message' => 'si creado',
                            'user' => $taraloja
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => 400,
                            'message' => 'No creado',
                            'user' => $taraloja
                        );
                    }

                }



            }
        }

        return response()->json($data, $data['code']);
    }


    public function getAll_tarloja(Request $request){
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = Validator::make($params_array, []);
        if($validate->fails()){
            $signup = array(
                'status' => 'error',
                'code'   => 404,
                'message' => 'No!',
                'errors' => $validate->errors()
            );
        }else{
            $signup = $jwtAuth->getAll_taraloja();
        }
        return response()->json($signup, 200);
    }

}
