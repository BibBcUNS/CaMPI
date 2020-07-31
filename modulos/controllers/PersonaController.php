<?php

namespace app\controllers;

use Yii;
use app\models\Persona;
use app\models\Usuario;
use app\models\MasDatos;
use app\models\PersonaHistory;
use app\models\PersonaSearch;
use app\models\TipoUsuario;
use app\models\Biblioteca;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\data\ArrayDataProvider;
use mdm\admin\components\Helper;
use app\rbac\LibraryDbManager;

/**
 * PersonaController implements the CRUD actions for Persona model.
 */
class PersonaController extends Controller
{
    /**
     * @inheritdoc
     */

    /**
     * Lists all Persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionViewDgsi($id) {

        try {
            $model = $this->findModel($id);
            $datos_dgsi = Yii::$app->dbDgsi->createCommand("SELECT * FROM vw834_alumnos_bib where nro_documento='$model->numero_documento'")->queryOne();
	    $carreras = Yii::$app->dbDgsi->createCommand("SELECT * FROM vw834_alumnos_bib where nro_documento='$model->numero_documento'")->queryAll();
            
            return Json::encode($this->renderPartial('viewDgsi', [
                'model' => $model,
                'datos_dgsi' => $datos_dgsi,
        	'carreras'=>$carreras,
            ]));

        } catch (\Exception $exc) {
            return Json::encode("<span class='text-danger'>No es posible establecer la conexión con el servidor</span>");
        }
    }
    
    public function actionUpdateByUsername($username)
    {
	if ($model = Persona::findByUsername($username)) {
             return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    public function actionViewByUsername($username)
    {
	if ($model = Persona::findByUsername($username)) {
             return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single Persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        if (Helper::checkRoute('verlogs')) {
            $log_datos_filiatorios = new ArrayDataProvider([
                'allModels' => PersonaHistory::find()
                                    ->orderBy(['date'=>SORT_DESC])
                                    ->where(['field_id' => $model->id])
                                    ->andWhere(['<>','field_name','updated_by']) // en el log no mostramos este campo porque no aporta información
                                    ->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            
            $log_mas_datos = new ArrayDataProvider([
                'allModels' => MasDatos::find()->orderBy(['created_at'=>SORT_DESC])->where(['persona_id' => $model->id])->all(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }
        else
            $log_datos_filiatorios = $log_mas_datos = [];
        
        return $this->render('view', [
            'model' => $model,
            'log_datos_filiatorios' => $log_datos_filiatorios,
            'log_mas_datos' => $log_mas_datos
        ]);
    }

    public function actionFoto($username)
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('content-type','image/png');
        $img_link = "http://campi-bc.int.uns.edu.ar/omp/circulacion/fotos/{$username}.jpg";
        
        if (@getimagesize($img_link)) {
            Yii::$app->response->data = file_get_contents($img_link);
            return Yii::$app->response;
        }
    }

    private function validar_persona_config($model) {
        return true;
        //$validacion_multiple = ActiveForm::validateMultiple($model->masDatos);   
        //return (count($validacion_multiple)==0); // Ok if 0 erros.
    }

    private function validar_datos_adicionales($model) {
        $validacion_multiple = ActiveForm::validateMultiple($model->masDatos);   
        return (count($validacion_multiple)==0); // Ok if 0 erros.
    }


    private function validar_usuarios($model) {
        $valid = true;
        foreach ($model->lista_usuarios as $usuario) {
            //var_dump($usuario);
            if (isset($usuario->tipo_usuario_id)) {
                $usuario->persona_id = $model->id;
                $vaid = $valid && $usuario->validate();
            }
        }
        //exit;
        return $valid;
    }


    private function load_datos_adicionales($model) {
        $model->masDatos = Yii::$app->request->post('MasDatos', []);
        // multiple input
        foreach (array_keys($model->masDatos) as $index) {
            $model->masDatos[$index] = new MasDatos();
        }

        Model::loadMultiple($model->masDatos, Yii::$app->request->post());
        // elimino los registros que no tienen definido ningún valor (valor == '')
        foreach ($model->masDatos  as $index => $dato) {
            if ($dato->valor=='' && $dato->campo_adicional_id=='') {
                unset($model->masDatos[$index]);
            }
        }

        // Si o si tiene que haber un Objeto para que TabularInput pueda generar el formulario.
        return true;
    }


    /*private function load_usuario($model) {
        $tipo_usuario= Yii::$app->request->post('Usuario', []);
        if ($tipo_usuario & TipoUsuario::findOne($tipo_usuario['tipo_usuario_id']) !== null) {
            $usuario = Usuario::findOne($model->id);
            if ($usuario === null) {
                $usuario = new Usuario;
                $usuario->persona_id = $model->id;
            }
            $usuario->tipo_usuario_id = $tipo_usuario['tipo_usuario_id'];
            //$usuario->save();
            return $usuario;
        }
        else
            return false;
    }*/

    private function normaliza ($cadena){
        setlocale(LC_ALL, 'en_US.UTF8');
        $cadena= preg_replace("/[^A-Za-z0-9 @,._-]/", '',iconv('UTF-8', 'ASCII//TRANSLIT', $cadena));
        return urlencode($cadena);
    }

    public function array_to_url_params($arreglo) {
        $parametros = [];
        foreach ($arreglo as $nombre => $valor) {
            $parametros [] = "{$nombre}=".$this->normaliza($valor);
        }
        return implode('&', $parametros);
    }

    private function guardarPersona($model, $save_remote = true) {
        if ($model->load(Yii::$app->request->post())) {
            $model->persona_config->load(Yii::$app->request->post());
            /*var_dump($model->lista_usuarios);
            echo "<hr>";
            var_dump(Yii::$app->request->post('Usuario'));exit;*/
            Model::loadMultiple($model->lista_usuarios, Yii::$app->request->post());
            $this->load_datos_adicionales($model);
            $todo_ok = true;
            $error_messages=[];
            $success_messages=[];
            if ($model->validate() && $this->validar_datos_adicionales($model)
                && $this->validar_usuarios($model) && $this->validar_persona_config($model)) {
                $model->username = ($model->tipoDocumento->tipo).$model->numero_documento;
                $model->save();
                $model->saveDatosAdicionales();
                $model->saveUsuarios();
                //Yii::$app->session->addFlash('success', "<span class='glyphicon glyphicon-ok'></span> Los datos se guardaron correctamente en la <b>BD Centralizada</b> ");
                $success_messages[] = "<span class='glyphicon glyphicon-ok'></span> <b class='col-md-4'>BD Centralizada</b> Los datos se guardaron correctamente.";
                // El TabularInput requiere si o si uno Objeto para armar el formulario
                
                // acá se podrían definir criterios dinámicos. Es decir, que se cree el registro donde no está y se actualice donde si está.
                
                if ($save_remote) {
                    $bibliotecas = Biblioteca::bibliotecasHabilitadas();
                    foreach ($bibliotecas as $biblioteca) {
                        //echo $biblioteca->id.'--';
                        //$usuario = $model->usuario_en($biblioteca->id);
                        $usuario = $model->usuarios_post_get_por_biblioteca($biblioteca->id);
                        $url_update_lector = "http://{$biblioteca->url_campi}/omp/cgi-bin/wxis.exe/omp/webservices/?IsisScript=webservices/lector-new-update.xis&id_operador=admin&".
                                $this->array_to_url_params([
                                    'credencial'        => $model->username,
                                    'domicilio'         => $model->domicilio,
                                    'nombre'            => $model->apellido.', '.$model->nombre,
                                    'email'             => $model->email,
                                    'telefono'          => $model->telefono,
                                    'notificacion_proximo_a_vencer'
                                                        => $model->persona_config->notificacion_proximo_a_vencer,

                                    'categoria'         => ($usuario)?$usuario->categoria:'',
                                    'eliminar_sanciones'=> ($usuario)?$usuario->eliminar_sanciones:'',
                                ]);

                        try {
                            $renovaciones_json = file_get_contents($url_update_lector);
                            $resultado=json_decode($renovaciones_json);

                            if (is_null($resultado)) {
                                $error_messages[] = "Error en <b>\"$biblioteca->nombre\"</b>: <i>{$biblioteca->url_campi}</i>";
                                $todo_ok = false;
                            }
                            elseif ($resultado->estado == 'error') {
                                $error_messages[] = "Error en <b>\"$biblioteca->nombre\"</b>({$biblioteca->url_campi}): <i>{$resultado->mensaje}</i>";
                                $todo_ok = false;
                            }
                            else {
                                $success_messages[] = "<span class='glyphicon glyphicon-ok'></span> <b class='col-md-4'>$biblioteca->nombre </b>".$resultado->message;
                            }
                        } catch (ErrorException $e) {
                            $error_messages[] = "Error de conexión en <b>\"$biblioteca->nombre\"</b>: <i>http://{$biblioteca->url_campi}/omp/cgi-bin/...</i>";
                            $todo_ok = false;
                        }
                    }
                }

                if (count($success_messages)>0)
                    Yii::$app->session->addFlash('success', implode("<br>",$success_messages));

                if (count($error_messages)>0)
                    Yii::$app->session->addFlash('error', implode("<br>",$error_messages));
                //exit;
                return $todo_ok;
            }
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->lista_usuarios = $model->usuariosEditables;
        if ($this->guardarPersona($model)) { // esto es si se guarda correctamete
            return $this->redirect(['view', 'id' => $model->id]);
        }
        //Esto lo tengo que poner porque Tabular Inpur requiere un objeto para armar el formulario.
        if (count($model->masDatos)==0) $model->masDatos = [new MasDatos()];
        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionCreate()
    {
        $model = new Persona();
        $model->lista_usuarios = $model->usuariosEditables;
        $model->bloqueo_actualizacion   = 0;
        $model->tipo_persona            = TipoUsuario::USUARIO_INDIVIDUAL;  // Usuario Individual

        if ($this->guardarPersona($model)) { // esto es si se guarda correctamete
            return $this->redirect(['view', 'id' => $model->id]);
        }
        //Esto lo tengo que poner porque Tabular Inpur requiere un objeto para armar el formulario.
        if (count($model->masDatos)==0) $model->masDatos = [new MasDatos()];
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Persona model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
