<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PersonaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="persona-search row">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nombre',['options'=>['class'=>'col-md-3']]) ?>
    
    <?= $form->field($model, 'apellido',['options'=>['class'=>'col-md-3']]) ?>

    <?= $form->field($model, 'email',['options'=>['class'=>'col-md-2']]) ?>

    <?= $form->field($model, 'telefono',['options'=>['class'=>'col-md-2']]) ?>

    <?php // echo $form->field($model, 'domicilio') ?>

    <?php // echo $form->field($model, 'numero_documento') ?>

    <?php // echo $form->field($model, 'tipo_documento_id') ?>

    <div class="form-group col-md-3">
        <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span> Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('<span class="glyphicon glyphicon-erase"></span> Limpiar', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
