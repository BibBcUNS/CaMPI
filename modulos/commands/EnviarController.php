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



class EnviarController extends Controller
{
    
    public function actionIndex()
    {
        
        $bibliotecas = Biblioteca::bibliotecasHabilitadas();
        
        foreach ($bibliotecas as $biblioteca) {
           
            //Este if se coloca para probar unicamente con la url de campi humanidades
            if ($biblioteca->id==2){

                                
                $target_url = 'http://'.$biblioteca->url_campi.'/receive_files/receive_files.php';

                $file_name_with_full_path = realpath('./persona.csv');

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
            





















//  ------------------------------------------------
        //  Declaraciones
        // ------------------------------------------------
//        $datos_dgsi = Yii::$app->dbDgsi->createCommand("SELECT nro_documento FROM vw834_alumnos_bib")->queryAll();
//        $registros_nuevos = 0;
//        $registros_presentes = 0;
//        $mapeo_tipo_doc = [
//            // tipo_dgsi => id_tipo_doc_persona
//            'Pas' => 4, 
//            'LC' => 3, 
//            'LE' => 2, 
//            'CI' => 5, 
//            'DNT' => 6, 
//            'DNI' => 1, 
//        ];
//        $persona_csv = "runtime/persona.csv";
//        file_put_contents($persona_csv, "");
//        
//        function domicilio ($registro) {
//            return  $registro['calle_per_lect'].' '.$registro['numero_per_lect'].' '.$registro['piso_per_lect'].' '.$registro['unidad_per_lect'];
//        }
//        
//        //-------------------------------------------------------------
//        Yii::info('Registros a agregar a la tabla persona','comandos');
//        Yii::info('--------------------------------------','comandos');
//        
//        foreach ($datos_dgsi as $datos) {
//            $nro_documento = $datos['nro_documento'];
//            
//            if (!Persona::find()->where(['numero_documento'=>$nro_documento])->exists()) {
//                
//                $registros_nuevos++;
//                
//                $registro_dgsi = Yii::$app->dbDgsi->createCommand("SELECT * FROM vw834_alumnos_bib where nro_documento='$nro_documento'")->queryOne();
//                               
//                $registro_dgsi = array_map('trim', $registro_dgsi);
//                
//                $persona = new Persona();                
//                $persona-> nombre = $registro_dgsi['nombres'];
//                $persona-> apellido = $registro_dgsi['apellido'];
//                $persona-> email = $registro_dgsi['e_mail'];
//                $persona-> telefono = $registro_dgsi['te_per_lect'];
//                $persona-> domicilio = domicilio ($registro_dgsi);
//                $persona-> numero_documento = $registro_dgsi['nro_documento'];
//                $persona-> tipo_documento_id = $mapeo_tipo_doc[$registro_dgsi['tipo_documento']];
//                $persona-> username = ($persona->tipoDocumento->tipo).$persona->numero_documento;
//                
//                if ($persona->save()){
//                    
//                    $linea_log= $persona->username.'---'.$persona->apellido.'---'.$persona->nombre;
//                    Yii::info($linea_log,'comandos');
//                    
//                    $linea_csv =    $persona->apellido.', '.$persona->nombre.'['.
//                                    $persona->username.'['.
//                                    'SIN_CATEGORIA'.'['.
//                                    $persona->domicilio.'['.
//                                    $persona->telefono.'['.
//                                    'Habilitado'.'['.
//                                    $persona->email.'['.
//                                    date("d/m/Y").'['.
//                                    'AYE'.PHP_EOL                                    
//                                    ;
//
//                    file_put_contents($persona_csv, $linea_csv,FILE_APPEND);
//                                        
//                }
//                else {
//                    Yii::error($linea_log,'comandos');
//                }
//            }
//            
//        }
//        
//        Yii::info('------------------------------------','comandos');
//        Yii::info("Registros Nuevos: $registros_nuevos",'comandos');
//        echo "Registros Nuevos: $registros_nuevos \n";
//        echo "Registros Presentens: $registros_presentes \n";
//        
//        $client = new Soap_Client( null, array('uri'=>'http://campi-humanidades.int.uns.edu.ar/receive_files/receive_files.php') );
//        $client->receiveFile( base64_encode( file_get_contents( 'somepath' )) );
        
        return ExitCode::OK;
    }

//    
}
