<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Taraloja;
class Subsi15Controller extends Controller
{
 public function getCategoria(Request $request){
    $jwtAuth = new \JwtAuth();

    $json = $request->input('json', null);
    $params = json_decode($json);
    $params_array = json_decode($json, true);

    $validate = Validator::make($params_array, [
        'documento' =>'required'
    ]);
    if($validate->fails()){
        $signup = array(
            'status' => 'error',
            'code'   => 404,
            'message' => 'No!',
            'errors' => $validate->errors()
        );
    }else{
        $signup = $jwtAuth->getCategoria($params_array['documento']);
    }
    return response()->json($signup, 200);
 }   
}
