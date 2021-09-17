<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use app\models\Biblioteca;
use mdm\admin\components\Helper;
use yii\web\Session;

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
    <?php if ($cat = Yii::$app->session->get('library')): ?>
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Datos Internos</h3>
        </div>
        <div class="panel-body">
            <?= $this->render('__usuarios', [
                    'form'      => $form,
                    'model'     => $model,
                    'usuario'   => $model->usuario,
                ])
            ?>
        </div>
      </div>
    <?php endif; ?>
</div>

    <br>

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
