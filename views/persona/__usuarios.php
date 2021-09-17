<?php
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use app\models\TipoUsuario;
	use app\models\Usuario;
	use kartik\switchinput\SwitchInput;
?>


<div class="col-md-5">
	<div class="panel panel-<?=(isset($usuario->tipoUsuario))?'success':'default' ?>">
	  <div class="panel-heading">
	    <h3 class="panel-title">
	    	<!-- /* Ahora no existe una relación con la tabla biblioteca $usuario->tipoUsuario->biblioteca->nombre -->
	    		<?= $usuario->biblioteca->nombre ?>
	    	</h3>
	  </div>
	  <div class="panel-body">
	  	 <?php 
	  	 	// ID usuario
	     	if (! $usuario->isNewRecord) {
                echo Html::activeHiddenInput($usuario, "id");
            }

            echo $form->field($usuario, "tipo_usuario_id")
	     		->dropDownList(
	     			//ArrayHelper::map(TipoUsuario::find()->where(['biblioteca_id'=>$usuario->biblioteca->id])->all(), 'id', 'nombre'),
	     			$usuario->biblioteca->mapTiposDeUsuario,
	     			[
	     				'value'=>$usuario->tipo_usuario_id,
	     				'prompt' => 'Seleccionar categoría ...',
	     				'style'=>'padding:0px',
	     			]
	     	);
	     	// Observaciones
	     	echo $form->field($usuario, "notas")->textarea(['rows' => '3']);
     	?>
     	<?php if (!is_null($usuario->prestamos)) {
     		echo "<div class='row'><div class='col-md-12'>";
     		echo "<b>Prestamos vigentes</b>: <ul class='text-success'>";
     		foreach ($usuario->prestamos as $prestamo) {
     			echo "<li>$prestamo</li>";
     		}
     		echo "</ul><br>";
		    echo "</div></div>";
     	}?>
     	<?php if (!is_null($usuario->sanciones)) {
     		echo "<div class='row'><div class='col-md-12'>";
     		echo "<b>Sanciones</b>: <ul class='text-danger'>";
     		foreach ($usuario->sanciones as $sancion) {
     			echo "<li>$sancion</li>";
     		}
     		echo "</ul><br>";
     		echo $form->field($usuario, "eliminar_sanciones")->widget(SwitchInput::classname(), [
		        'name'=>'eliminar_sanciones',
		        'options'=>['class'=>'col-md-5'],
		        'pluginOptions'=>[
		        	'size' => 'mini',
		            'handleWidth'=>100,
		            'offText'=>'Dejar como está',
		            'onText'=>'<i class="glyphicon glyphicon-ok-circle"></i> Eliminar sanciones',
		            'offColor' => 'default',
		            'onColor' => 'success',
		            'handleWidth' => 140
		        ]
		    ])->label(false);
		    echo "</div></div>";
     	}?>
     	<div class="hidden">En <b><?= $usuario->biblioteca->url_campi ?></b></div>
	  </div>
	</div>
</div>