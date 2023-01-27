<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\Gener02;
use App\Models\Conta19;
use App\Models\Conta28;
use App\Models\nomin02;
use App\Models\nomin02Emp;
use App\Models\Conta12;
use App\Models\Conta20;
use App\Models\Tiposaloja;
use App\Models\Taraloja;

/* require_once("/resources/libs/UserReportPdf/UserReportPdf.php");
require_once("/resources/libs/UserReportExcel/UserReportExcel.php");
 */
class JwtAuth{

    public $key;
    
    
    public function __construct(){
        $this->key = '_clave_-32118';
    }

    function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);

        $cadena = str_replace(
            array('¤', '¥'),
            array('N', 'N'),
            $cadena
            );
		
		return $cadena;
	}


    public function getConta19_($codact){

        $datos=array();
        $signup = false;
        $conta19 = Conta19::where('codact','=',$codact)
        ->where('cnt','01')
        ->get();

        if(sizeof($conta19)>0){
            $signup = true;
        }else{
            $token = array(
                'status'=>'error',
                'message'=>'Activo no encontrado',
                'bendera'=>false
            );   
        }

        if($signup){
            foreach($conta19 as $c19){
                 $conta12 = Conta12::where('claact',$c19->claact)
                ->where('cnt','01')
                ->first();

                $conta20 = Conta20::where('tipo',$conta12->tipo)
                ->where('cnt','01')
                ->first();

                if($conta20){
                    $con_det = $conta20->detalle;
                }else{
                    $con_det = '';
                }


                $conta28 = Conta28::where('coddep','=',trim($c19->coddep))
                ->first();
                
                if($conta28){
                    $c19_detalle = $c19->coddep.' - '.$conta28->detalle;
                }else{
                    $c19_detalle = '';
                }

                
                $nomin02 = nomin02Emp::where('docemp','=',trim($c19->cedtra))->first(); 

                if($nomin02){
                    $nombre = $this->eliminar_acentos($nomin02->priape).' '.$this->eliminar_acentos($nomin02->segape).' '.$this->eliminar_acentos($nomin02->nomemp).' '.$this->eliminar_acentos($nomin02->segnom);

                }else{
                    $nombre = '';
                }

                $token = array(
                     'codact'=>$c19->codact,
                     'claact'=>$con_det,
                     'coddep'=>$c19_detalle,
                     'nombre'=>$nombre
                );

                array_push($datos, $token);


                $datos = $this->convert_from_latin1_to_utf8_recursively($datos);
            }
       

        }else{
            $datos = array(
                'status'=>'error',
                'message'=>'Activo no encontrado',
                'bendera'=>false
            );   
        }
        $jwt = JWT::encode($datos, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
    
        $data = $decoded;

        return $data;
    }


    public function traerCedula($cedtra){

        $nomin02 = nomin02Emp::where('docemp',trim($cedtra))->get();

        
        $signup = false;
        if(sizeof($nomin02)>0){
            $signup = true;
        }else{

            $token = array(
                'status'=>'error',
                'message'=>'Usuario no encontrado',
                'bendera'=>false
            );
            
        }
        if($signup){
            foreach($nomin02 as $que){
                $token = array(
                    'docemp'=>$que->docemp,
                    'ciuced'=>$que->ciuced,
                    'coddoc'=>$que->coddoc,
                    'priape'=>$this->eliminar_acentos($que->priape),
                    'segape'=>$this->eliminar_acentos($que->segape),
                    'nomemp'=>$this->eliminar_acentos($que->nomemp),
                    'segnom'=>$this->eliminar_acentos($que->segnom),
                    'fecnac'=>$que->fecnac,
                    'codciu'=>$que->codciu,
                    'codsex'=>$que->codsex,
                    'estciv'=>$que->estciv,
                    'codzon'=>$que->codzon,
                    'coddep'=>$que->coddep,
                    'coddes'=>$que->coddes,
                    'tipnom'=>$que->tipnom,
                    'tipcon'=>$que->tipcon,
                    'contra'=>$que->contra,
                    'codsal'=>$que->codsal,
                    'bandera'=>true,
                );

            }
        }

        return $this->convert_from_latin1_to_utf8_recursively($token);

    }

    function write_to_console($data) {
        $console = $data;
        if (is_array($console)){
            $console = implode(',', $console);
        }
        echo "<script>console.log('Console: " . $console . "' );</script>";
    }
    


    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
       if (is_string($dat)) {
          return utf8_encode($dat);
       } elseif (is_array($dat)) {
          $ret = [];
          foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);
 
          return $ret;
       } elseif (is_object($dat)) {
          foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);
 
          return $dat;
       } else {
          return $dat;
       }
    }

    public function signup($usuario, $clave, $getToken = null){
        //Buscar
        $gener02 = Gener02::where([
            'usuario' =>$usuario,
            'clave' =>$clave
        ])->first();
        //Comprobar si son correctas
        $signup = false;
        if(is_object($gener02)){
            $signup = true;
        }
        //Generar el token con los datos del identificado
        if($signup){
            $token = array(
                'sub' => $gener02->usuario,
                'email' => $gener02->email,
                'name' => $gener02->nombre,
                'cedtra' => $gener02->cedtra,
                'iat' => time(),
                'exp' => time()+(7*24*60*60)
            );
            $jwt=JWT::encode($token, $this->key, 'HS256');
            //Devolver los datos identificados o el token, en funcion de un parametro
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if(is_null($getToken)){
                $data = $jwt;

            }else{
                $data = $decoded;
            }
        }else{
            $data = array(
                'status' => 'error',
                'message' => 'Login Incorrecto'
            );
        }
        return $data;
    }

    public function getAll_tiposaloja(){

        $tiposAloja = Tiposaloja::all();
        $token = array();
        if(sizeof($tiposAloja)>0){
            foreach($tiposAloja as $ta){
                $token1 = array(
                    'id'=>$ta->id,
                    'detalle'=>$ta->detalle,
                    'estado'=>$ta->estado,
                    'titulo'=>$ta->titulo,
                    'parrafo'=>$ta->parrafo,
                    'disponibles'=>$ta->disponibles,
                    'usuario'=>$ta->usuario,
                    'fecha'=>$ta->fecha
                );
                array_push($token, $token1);
            }
        }else{
            $token = array(
                'status' => 'error',
                'message' => 'Datos no encontrados'
            );
        }
        $jwt = JWT::encode($token, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
    
        $data = $decoded;

        return $data;
    }

    public function getTarifas($id){
        $taraloja = Taraloja::where('tiposaloja_id', $id)->get();
        $token = array();
        if(sizeof($taraloja)>0){
            foreach($taraloja as $ta){
                $token1 = array(
                    'tarA'=>number_format($ta->tarA),
                    'tarB'=>number_format($ta->tarB),
                    'tarC'=>number_format($ta->tarC),
                    'tarD'=>number_format($ta->tarD),
                    'tarE'=>number_format($ta->tarE),
                );
                $token = $token1;
            }
        }else{
            $token = array(
                'status' => 'error',
                'message' => 'Datos no encontrados'
            );
        }
        $jwt = JWT::encode($token, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
        $data = $decoded;
        return $data;
    }
    public function getAll_taraloja(){

        $taraloja = Taraloja::all();
        $token = array();
        if(sizeof($taraloja)>0){
            foreach($taraloja as $ta){
                $detaloja = Tiposaloja::where('id', $ta->tiposaloja_id)->first();
                $token1 = array(
                    'id'=>$ta->id,
                    'tiposaloja_id'=>$ta->tiposaloja_id,
                    'tiposaloja_id_detalle'=>$detaloja->detalle,
                    'tarA'=>$ta->tarA,
                    'tarB'=>$ta->tarB,
                    'tarC'=>$ta->tarC,
                    'tarD'=>$ta->tarD,
                    'tarE'=>$ta->tarE,
                    'usuario'=>$ta->usuario,
                    'fecha'=>$ta->fecha
                );
                array_push($token, $token1);
            }
        }else{
            $token = array(
                'status' => 'error',
                'message' => 'Datos no encontrados'
            );
        }
        $jwt = JWT::encode($token, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']); 
    
        $data = $decoded;

        return $data;
    }
}