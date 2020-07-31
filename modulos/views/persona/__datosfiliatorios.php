<?php 
    use yii\helpers\ArrayHelper;
    use app\models\TipoDocumento;
    use app\models\Persona;
    use borales\extensions\phoneInput\PhoneInput;
?>
<div class="row">
    <div class="col-md-6"><?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md-6"><?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?></div>
</div>

<div class="row">
     <div class="col-md-6"><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>
     <div class="col-md-6"><?= $form->field($model, 'domicilio')->textInput(['maxlength' => true]) ?></div>
</div>
<div class="row">

     <div class="col-md-2"><?= $form->field($model, 'tipo_documento_id')->dropDownList(
            ArrayHelper::map(TipoDocumento::find()->all(), 'id', 'tipo'),
            [   'prompt' => 'Tipo de Documento',
                'style'=>'padding:0px',
                'readonly' => (!$model->isNewRecord), 
                'disabled' => (!$model->isNewRecord),
            ]); ?>
     </div>
     <div class="col-md-3"><?= $form->field($model, 'numero_documento')->textInput([
        'maxlength' => true,
        'readonly' => (!$model->isNewRecord), 
        'disabled' => (!$model->isNewRecord),
    ]) ?></div>

     <div class="col-md-5">
        <label class="control-label" for="persona-telefono">
        <?=Yii::t('app', 'Teléfono')?></label>
        <?= $form->field($model, 'telefono')->widget(PhoneInput::className(), [
                                            'jsOptions' => [
                                                'nationalMode' => false,
                                                'preferredCountries' => Yii::$app->params['preferredCountries'],
                                                'onlyCountries' => Yii::$app->params['onlyCountries'],

                                            ],
                                        ])->label(false); ?>
     </div>

</div>

<!--div class="row">
    <div class="col-md-12">
        <label class="control-label" for="persona-bloqueo_actualizacion">
        <?= $model->getAttributeLabel('bloqueo_actualizacion')?></label>
        <?= $form->field($model, 'bloqueo_actualizacion')->radioButtonGroup(
            Persona::bloqueoEstado(),
            [
                'class' => 'btn-group',
                //'disabledItems'=>[0,1,2],
                //'itemOptions' => ['labelOptions' => ['class' => 'btn btn-danger']]
            ]
    )->label(false)?>
    </div>
</div-->
