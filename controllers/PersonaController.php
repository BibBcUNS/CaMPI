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

    private function guardarPersona($model) {
        $usuario = $model->usuario;
        if ($model->load(Yii::$app->request->post()) && $usuario->load(Yii::$app->request->post())) {

            $model->persona_config->load(Yii::$app->request->post());
            $this->load_datos_adicionales($model);

            $error_messages=[];
            $success_messages=[];
            if ($model->validate() && $this->validar_datos_adicionales($model)
                && $usuario->validate() && $this->validar_persona_config($model)) {
                $model->username = ($model->tipoDocumento->tipo).$model->numero_documento;
                $model->save();
                $model->saveDatosAdicionales();
                $usuario->save();
                Yii::$app->session->addFlash('success', "<span class='glyphicon glyphicon-ok'></span> Los datos se guardaron correctamente");
                return true;
            }
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model->lista_usuarios = $model->usuariosEditables;
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
