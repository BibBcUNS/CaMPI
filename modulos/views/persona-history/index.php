<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonaHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historial datos filiatorios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'table',
            //'field_id',
            //'type',
            //'user_id',
            [
                'value'=>'persona.username',
                'label' => 'DNI',
                'attribute' => 'persona_username',
            ],
            [
                'value'=>'persona.apellido',
                'label' => 'Apellido',
                'attribute' => 'persona_apellido',
            ],
            [
                'value'=>'persona.nombre',
                'label' => 'Nombre',
                'attribute' => 'persona_nombre',
            ],
            /*'persona.apellido',
            'persona.nombre',*/
            'date',
            'field_name',
            'old_value:ntext',
            'new_value:ntext',
            'user.username',

            /*['class' => 'yii\grid\ActionColumn',
            'template'=>'{view}'],*/
        ],
    ]); ?>
</div>
