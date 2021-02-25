<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Biblioteca;
use app\models\User;
use app\models\TipoDocumento;
use kartik\switchinput\SwitchInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?>
    <div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
		    	<div class="panel-heading"><h4 class="row col-md-10">Perfil</h4><?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?></div>
		    	<div  class="panel-body">
		            <div class="col-md-12">
		            	<div class="row">
			    		<div class="col-md-4"><?= $form->field($model, 'tipo_documento_id')->dropDownList(
					            ArrayHelper::map(TipoDocumento::find()->all(), 'id', 'tipo'),
					            [   'prompt' => 'Tipo de Documento',
					                'style'=>'padding:0px',
					                'readonly' => (!$model->isNewRecord), 
					                'disabled' => (!$model->isNewRecord),
					            ]); ?>
					     </div>
					     <div class="col-md-8"><?= $form->field($model, 'numero_documento')->textInput([
					        'maxlength' => true,
					        'readonly' => (!$model->isNewRecord), 
					        'disabled' => (!$model->isNewRecord),
					    ]) ?></div>
						</div>	
				    	<?= $form->field($model, 'username')->textInput() ?>
				    	<?= $form->field($model, 'nombre')->textInput() ?>
				    	<?= $form->field($model, 'apellido')->textInput() ?>
				    	<?= $form->field($model, 'email')->textInput() ?>
					</div>

		            <div class="col-md-12">
		            	<?php if($library=Yii::$app->session->get('library')) {
		            		//var_dump(User::rolesDisponibles());exit;
					    	echo $form->field($model,"permiso")
		    						 ->dropDownList(User::rolesDisponibles(), [
					    					'prompt' => 'Permiso...' ,
					    			 ])->label("Rol asignado en ".$library->nombre);
						} ?>
					</div>
				</div>
	    	</div>
	    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
