<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\Biblioteca */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="biblioteca-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prefijo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url_campi')->textInput(['maxlength' => true]) ?>

    <?php //= $form->field($model, 'habilitar_update')->textInput() ?>

    <?= $form->field($model, 'habilitar_update')->widget(SwitchInput::classname(), [
        'name'=>'habilitar_update',
        'pluginOptions'=>[
            'handleWidth'=>100,
            'onText'=>'Activado',
            'offText'=>'No actualizar',
            'onColor' => 'success',
            'offColor' => 'danger',
        ]
    ])  ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
