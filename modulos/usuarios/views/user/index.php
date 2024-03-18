<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Nuevo', ['admin/user/signup'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'options' => ['class' => 'gridview_click','data-url_view'=>Url::to(['view'])],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
            /*[
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'font-size:1.2em','class'=>'col-md-1 text-center']
            ],*/
        ],
    ]); ?>
</div>
