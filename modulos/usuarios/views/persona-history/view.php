<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PersonaHistory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Model Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date',
            'table',
            'field_name',
            'field_id',
            'old_value:ntext',
            'new_value:ntext',
            //'type',
            'user.username',
        ],
    ]) ?>

</div>
