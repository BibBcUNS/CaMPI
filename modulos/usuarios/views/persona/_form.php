<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use app\models\Biblioteca;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\Persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="persona-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--?= $form->errorSummary($model); ?-->
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Datos Filiatorios</h3>
          </div>
          <div class="panel-body">
            <?= $this->render('__datosfiliatorios', [
                        'model' => $model,
                        'form'  => $form,
                    ]);
            ?>
          </div>
        </div>
    </div>
    <div class="col-md-6">
        <!--div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Configuración</h3>
          </div>
          <div class="panel-body">
            <?= $this->render('__persona_config', [
                        'model' => $model->persona_config,
                        'form'  => $form,
                    ]);
            ?>
          </div>
        </div-->
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Otros Datos</h3>
          </div>
          <div class="panel-body">
            <?= $this->render('__masdatos', [
                        'model' => $model,
                        'form'  => $form,
                    ]);
            ?>
          </div>
        </div>
    </div>
</div>
    <?php $bibliotecas = Biblioteca::bibliotecasHabilitadas(); ?>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Usuarios definido en bibliotecas</h3>
      </div>
      <div class="panel-body">
        <?php 
          $index = 0;
          foreach ($model->lista_usuarios as $usuario) {
              echo $this->render('__usuarios', [
                  'model'     => $model,
                  'form'      => $form,
                  'usuario'   => $usuario,
                  'index'     => $index
              ]);
              $index++;
        } ?>
      </div>
    </div>
    </div>

    <br>
    <?php
      /*$lista_bibliotecas = "";
      foreach ($bibliotecas as $biblioteca) {
          $lista_bibliotecas .= "<li>{$biblioteca->nombre}</li>";
        <br>Se va a actualizar en: <b><ul><?= $lista_bibliotecas ?></ul></b> 
      }*/
    ?>
    <div class="form-group">
        <div class="col-md-3">
          <?= Html::submitButton(Yii::t('app', '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Guardar'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
        </div>
        <div class="col-md-3">
          <?= Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancelar', ['/persona'], ['class'=>'btn btn-warning']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
