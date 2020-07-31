<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Persona;
use app\models\Biblioteca;



class HelloController extends Controller
{
    
    public function actionIndex()
    {
        // ------------------------------------------------
        //  Declaraciones
        // ------------------------------------------------
        $datos_dgsi = Yii::$app->dbDgsi->createCommand("SELECT nro_documento FROM vw834_alumnos_bib")->queryAll();
        $registros_nuevos = 0;
        $registros_presentes = 0;
        $mapeo_tipo_doc = [
            // tipo_dgsi => id_tipo_doc_persona
            'Pas' => 4, 
            'LC' => 3, 
            'LE' => 2, 
            'CI' => 5, 
            'DNT' => 6, 
            'DNI' => 1, 
        ];
        $persona_csv = "runtime/persona.csv";
        file_put_contents($persona_csv, "");
        
        function domicilio ($registro) {
            return  $registro['calle_per_lect'].' '.$registro['numero_per_lect'].' '.$registro['piso_per_lect'].' '.$registro['unidad_per_lect'];
        }
        
        //-------------------------------------------------------------
        Yii::info('Registros a agregar a la tabla persona','comandos');
        Yii::info('--------------------------------------','comandos');
        
        foreach ($datos_dgsi as $datos) {
            $nro_documento = $datos['nro_documento'];
            
            if (!Persona::find()->where(['numero_documento'=>$nro_documento])->exists()) {
                
                $registros_nuevos++;
                
                $registro_dgsi = Yii::$app->dbDgsi->createCommand("SELECT * FROM vw834_alumnos_bib where nro_documento='$nro_documento'")->queryOne();
                               
                $registro_dgsi = array_map('trim', $registro_dgsi);
                
                $persona = new Persona();                
                $persona-> nombre = $registro_dgsi['nombres'];
                $persona-> apellido = $registro_dgsi['apellido'];
                $persona-> email = $registro_dgsi['e_mail'];
                $persona-> telefono = $registro_dgsi['te_per_lect'];
                $persona-> domicilio = domicilio ($registro_dgsi);
                $persona-> numero_documento = $registro_dgsi['nro_documento'];
                $persona-> tipo_documento_id = $mapeo_tipo_doc[$registro_dgsi['tipo_documento']];
                $persona-> username = ($persona->tipoDocumento->tipo).$persona->numero_documento;
                
                if ($persona->save()){
                    
                    $linea_log= $persona->username.'---'.$persona->apellido.'---'.$persona->nombre;
                    Yii::info($linea_log,'comandos');
                    
                    $linea_csv =    $persona->apellido.', '.$persona->nombre.'['.
                                    $persona->username.'['.
                                    'SIN_CATEGORIA'.'['.
                                    $persona->domicilio.'['.
                                    $persona->telefono.'['.
                                    'Habilitado'.'['.
                                    $persona->email.'['.
                                    date("d/m/Y").'['.
                                    'AYE'.PHP_EOL                                    
                                    ;
                    
                    $linea_csv = mb_convert_encoding($linea_csv, 'iso-8859-1');
                    file_put_contents($persona_csv, $linea_csv,FILE_APPEND);
                                        
                }
                else {
                    Yii::error($linea_log,'comandos');
                }
            }
            
        }
        
        Yii::info('------------------------------------','comandos');
        Yii::info("Registros Nuevos: $registros_nuevos",'comandos');
        echo "Registros Nuevos: $registros_nuevos \n";
        echo "Registros Presentens: $registros_presentes \n";
        
        $bibliotecas = Biblioteca::bibliotecasHabilitadas();
        
        foreach ($bibliotecas as $biblioteca) {
           
            //Este if se coloca para probar unicamente con la url de campi humanidades
            if ($biblioteca->id==4){

                                
                $target_url = 'http://'.$biblioteca->url_campi.'/receive_files/receive_files.php';

                //$file_name_with_full_path = realpath('./persona.csv');
                $file_name_with_full_path = realpath($persona_csv);

                $file = new \CURLFile($file_name_with_full_path);

                $post = array('extra_info' => 'persona.csv','file'=> $file);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL,$target_url);
                curl_setopt($ch, CURLOPT_POST,1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

                $result=curl_exec ($ch);
                
                curl_close ($ch);
                
                echo $result;
                           
            }
        }
        

        return ExitCode::OK;
    }

//    public function actionActualizarTodos()
//    {
//        function normaliza ($cadena){
//            setlocale(LC_ALL, 'en_US.UTF8');
//            $cadena= preg_replace("/[^A-Za-z0-9 ]/", '',iconv('UTF-8', 'ASCII//TRANSLIT', $cadena));
//            return urlencode($cadena);
//        }
//        function array_to_url_params($arreglo) {
//            $parametros = [];
//            foreach ($arreglo as $nombre => $valor) {
//                $parametros [] = "{$nombre}=".
//                    (($nombre=='email')
//                        ? $valor // porque normaliza elimina el @
//                        : normaliza($valor));
//            }
//            return implode('&', $parametros);
//        }
//
//        function microtime_float()
//        {
//            list($useg, $seg) = explode(" ", microtime());
//            return ((float)$useg + (float)$seg);
//        }
//        $tiempo_inicio = microtime_float();
//
//        $wxis_script = 'update';
//        $bibliotecas = Biblioteca::bibliotecasHabilitadas();
//        $array = [];
//
//        //for ($i=1; $i < 10; $i++) { 
//        $count=0;
//        foreach(Persona::find()->select(['id','username','apellido','nombre','telefono','email'])->each(100) as $persona) {
//            //if ($count++ == 1000) break;
//            file_put_contents('archivo.csv',
//                    $persona->username.';'.
//                    $persona->domicilio.';'.
//                    $persona->apellido.', '.$persona->nombre.';'.
//                    str_replace(' ','',$persona->email).';'.
//                    $persona->telefono."\n",
//                FILE_APPEND);
//
//                /*
//                $url_update_lector = "http://10.10.1.24/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/auto-lector-{$wxis_script}.xis&id_operador=admin&".
//                                    array_to_url_params([
//                                        'credencial'=> $persona->username,
//                                        'domicilio' => $persona->domicilio,
//                                        'nombre'    => $persona->apellido.', '.$persona->nombre,
//                                        'email'     => str_replace(' ','',$persona->email),
//                                        'telefono'  => $persona->telefono,
//                                    ]);
//                $update_json = file_get_contents($url_update_lector);
//                $resultado=json_decode($update_json);
//                //var_dump($resultado);
//                //$persona->save();
//
//                //echo $persona->apellido."\n";
//                */
//                
//        }
//        $tiempo_fin = microtime_float();
//        $tiempo = $tiempo_fin - $tiempo_inicio;
//
//        echo "Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);
//    }
}
