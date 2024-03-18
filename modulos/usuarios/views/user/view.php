<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="col-md-6"><h1><?= Html::encode($this->title) ?></h1></div>

    <div class="col-md-8"><p>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Borrar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea borrar el usuario?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<span class="glyphicon glyphicon-cog"></span> Password', ['admin-change-password', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute'=>'status',
                'value'=> function ($model) {
                    return ($model->status)
                        ?'<span class="text-success"><b>Activo</b></span>'
                        :'<span class="text-danger"><b>Inacivo</b></span>';
                },
                'format'=>'raw'
            ],
            /*'created_at',
            'updated_at',*/
        ],
    ]) ?>
    </div>

    <div class="col-md-8">
    <h2>Permisos asignados en cada biblioteca</h2>
    <?= GridView::widget([
        'dataProvider' => $listaBibliotecas,
        'layout'=> "{items}",
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'nombre',
                'label'=>'Biblioteca'
            ],
            [
                'label' => 'Permisos',
                'value' =>  function ($biblioteca) use ($model) {
                    if (isset($model->permisosEfectivos[$biblioteca->id]))
                        return $model->permisosEfectivos[$biblioteca->id];
                    else
                        return null;
               }
            ]
           //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>

</div>
