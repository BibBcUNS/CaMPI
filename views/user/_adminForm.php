<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Biblioteca;
use app\models\User;
use app\models\TipoDocumento;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?>
    <div class="row">
		<div class="col-md-6">
			<div class="panel panel-default"">
		    	<div class="panel-heading"><h4>Perfil</h4></div>
		    	<div  class="panel-body">
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

				            <?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
				            'name'=>'status',
				            'pluginOptions'=>[
				                'class'=>'col-sm-4',
				                'handleWidth'=>60,
				                'onText'=>'Activo',
				                'offText'=>'Inactivo',
				                'onColor' => 'success',
				                'offColor' => 'danger',
				            ]
				        ])  ?>
				</div>
	    	</div>
	    </div>
	    <div class="col-md-6">
			<div class="panel panel-default">
		    	<div class="panel-heading"><h4>Permisos</h4></div>
		    	<div  class="panel-body">
		    		<table  class="col-md-12">
			    		<?php $permisosEfectivos = $model->permisosEfectivos ?>
			    		<?php foreach(Biblioteca::find()->all() as $biblioteca):?>
			    		<tr>
			    			<td class="col-md-6"><?=$biblioteca->nombre?></td>
			    			<td class="col-md-6">
			    				<?php $rol_id = (isset($permisosEfectivos[$biblioteca->id]))
			    									?$permisosEfectivos[$biblioteca->id]
			    									:null;
			    				?>
			    				<?= $form->field($model,"permisos[$biblioteca->id]")
			    						 ->dropDownList(User::rolesDisponibles(), [
						    					'prompt' => 'Permiso...' ,
						    					'value' => $rol_id
						    			 ])->label(false);
						    	?>
							</td>
			    		</tr>
			    		<?php endforeach;?>
		    		</table>
		    	</div>
		    </div>
		</div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
