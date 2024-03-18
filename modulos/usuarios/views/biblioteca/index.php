<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BibliotecaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bibliotecas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biblioteca-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Biblioteca', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute'=>'nombre',
                'contentOptions' => ['style'=>'font-size:1.32em'],
            ],
            [
                'attribute'=>'prefijo',
                'contentOptions' => ['class'=>'col-md-1 text-center'],
            ],
            'url_campi:url',
            [
                'format'=>'raw',
                'label'=>'Actualizar',
                'contentOptions' => ['class'=>'text-center'],
                'headerOptions' => ['class'=>'text-center'],
                'value'=> function ($model) {
                        return ($model->habilitar_update)
                                ?'<span class="glyphicon glyphicon-ok text-success"></span>'
                                :'<span class="glyphicon glyphicon-ban-circle text-danger"></span>';
                    }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'font-size:1.2em','class'=>'col-md-1 text-center']
            ],
        ],
    ]); ?>
</div>
