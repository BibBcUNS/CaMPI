<?php 
use app\models\CampoAdicional;
use app\models\MasDatos;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use unclead\multipleinput\TabularInput;
use unclead\multipleinput\TabularColumn;
use unclead\multipleinput\MultipleInput;

	//$campos_adicionales = CampoAdicional::find()->all();

	echo TabularInput::widget([
	    'models' => $model->masDatos,
	    'addButtonPosition'=> MultipleInput::POS_HEADER,
	    'allowEmptyList'=>true,
	    'min'=>0,
	    'attributeOptions' => [
	        //'enableAjaxValidation' => true,
	        'enableClientValidation' => false,
	        'validateOnChange' => false,
	        'validateOnSubmit' => true,
	        'validateOnBlur' => false,
	    ],
	    'form' => $form,
	    'columns' => [
	        [
	            'name' => 'persona_id',
	            'type'  => TabularColumn::TYPE_HIDDEN_INPUT,
	        ],
	        [
	            'name' => 'campo_adicional_id',
	            'title' => 'Campo',
	            'type'  => 'dropDownList',
	            'items' => [''=>'Seleccionar...']+ArrayHelper::map(CampoAdicional::find()->all(), 'id', 'campo')
	        ],
	        [
	            'name' => 'valor',
	            'title' => 'Valor',
	            'enableError' => true
	        ]
	    ]
	]);

?>