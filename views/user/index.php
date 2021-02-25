<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Operadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php/*= Html::a('<span class="glyphicon glyphicon-plus"></span> Nuevo', ['admin/user/signup'], ['class' => 'btn btn-success']) */ ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Nuevo', ['user/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'options' => ['class' => 'gridview_click','data-url_view'=>Url::to(['view'])],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
            [ 
                'attribute' => 'status',
                'label' => 'Estado',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                     if ($model->status) {
                        return "<apam style='color:green'>Activo</span>";
                     }
                     else
                        return "<apam style='color:tomato'>Inactivo</span>";
                },
                'filter' => [10 => 'Activos', 0 => 'Inactivos'], 
                'filterInputOptions' => ['class' => 'form-control', 'prompt' => '- Todos -', 'format'=>'html'],
            ],
            /*[
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'font-size:1.2em','class'=>'col-md-1 text-center']
            ],*/
        ],
    ]); ?>
</div>
