<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Biblioteca */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Bibliotecas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biblioteca-view">

    <div class="row">
        <div class="col-md-8">
            <h1><span class="glyphicon glyphicon-book"></span> <?= Html::encode($model->nombre) ?></h1>
        </div>
        <div class="col-md-4"><br>
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Borrar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'prefijo',
                'url_campi:url',
                [
                    'attribute'=>'habilitar_update',
                    'format'=>'raw',
                    'value'=> function ($model) {
                        return ($model->habilitar_update)
                                ?'<span class="text-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Activado</span>'
                                :'<span class="text-danger"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> No actualizar</span>';
                    }
                ]
            ],
        ]) ?>
        </div>
    </div>

</div>
