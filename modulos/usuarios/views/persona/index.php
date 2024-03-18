<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="persona-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Nuevo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'options' => ['class' => 'gridview_click','data-url_view'=>Url::to(['view'])],
        /*'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => "id=$model->id"];
        },*/
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'numero_documento',
                'value'=>'tipoYNro'
            ],
            'nombre',
            'apellido',
            //'telefono',
            //'id',
            //'username',
            'email:email',
            // 'domicilio',
            // 'tipo_documento_id',

            /*[   
                'class' => 'yii\grid\ActionColumn',
                'header'=> 'Acciones',
                'headerOptions' => ['class'=>'text-center'],
                'contentOptions' => ['style' => 'font-size:1.2em','class'=>'col-md-1 text-center'],
                'template' => Helper::filterActionColumn('{view}{update}{delete}'),
            ],*/
        ],
    ]); ?>
</div>
