<?php
namespace app\controllers;
use yii\rest\ActiveController;
use app\models\Persona;

class ServicesController extends ActiveController
{
    public $modelClass = 'app\models\Persona';

    public function  actionPersonaConfig($id) {
    	$persona = Persona::findOne(['id' => $id]);
    	return $persona->persona_config;
    }
}