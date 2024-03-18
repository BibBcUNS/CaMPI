<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Biblioteca;
use app\models\User;
use kartik\switchinput\SwitchInput;

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
				    	<?= $form->field($model, 'username')->textInput() ?>
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
