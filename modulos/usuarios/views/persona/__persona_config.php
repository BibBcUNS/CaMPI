<?php 
    use yii\helpers\ArrayHelper;
    use app\models\TipoDocumento;
    use app\models\Persona;
    use kartik\switchinput\SwitchInput;

?>
<div class="row">
    <div class="col-md-12">

        <div class="row panel">
            <div class="col-md-10">
                <label class="control-label" for="persona-notificacion_proximo_a_vencer">   
                <?= $model->getAttributeLabel('notificacion_proximo_a_vencer')?></label>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'notificacion_proximo_a_vencer')->widget(SwitchInput::classname(), [
                    'pluginOptions' => [
                        'size'=>'mini',
                        'onText' => 'Si',
                        'offText' => 'No',
                        'onColor'  => 'success'
                    ]])
                    ->label(false)?>
            </div>
        </div>
        <div class="row panel">
            <div class="col-md-10">
                <label class="control-label" for="persona-notificacion_espera">   
                <?= $model->getAttributeLabel('notificacion_espera')?></label>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'notificacion_espera')->widget(SwitchInput::classname(), [
                    'pluginOptions' => [
                        'size'=>'mini',
                        'onText'    => 'Si',
                        'offText'   => 'No',
                        'onColor'  => 'success'
                    ]])
                    ->label(false)?>
            </div>
        </div>
    </div>
</div>
