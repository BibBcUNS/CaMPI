<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use kartik\tabs\TabsX;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;
use app\models\MasDatos;
use app\models\Biblioteca;
use app\rbac\LibraryDbManager;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Persona */


// obtengo las bibliotecas en las que tiene permiso el usuario logueado
if (!Yii::$app->user->isGuest) {
    $user_id = Yii::$app->user->identity->id;
    $libraries_ids = LibraryDbManager::userLibraries($user_id);
   // var_dump($libraries_ids);exit;
}
else
    $libraries_ids = [];

$this->title = $model->apellido.', '.$model->nombre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Personas'), 'url' => ['persona/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
        <p>
            <?= (Helper::checkRoute('update'))?
                Html::a(Yii::t('app', '<span class="glyphicon glyphicon-pencil"></span> Editar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']):
            '' ?>
            
            <?= (Helper::checkRoute('delete'))?
                Html::a(Yii::t('app', '<span class="glyphicon glyphicon-trash"></span> Borrar'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ])
            :'' ?>
        </p>
<div class="row">
    <div class="persona-view col-md-8">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php
            // Atributos visibles
            $visible_attributes = [
                'id', 'nombre', 'apellido', 'email:email', 'telefono', 'domicilio', 'numero_documento', 'tipoDocumento.tipo'
            ];

            // Atributos restringidos
            $restricted_attributes = [
                'createdBy.username', 'created_at:datetime', 'updatedBy.username', 'updated_at:datetime'
            ];
            
            if (Helper::checkRoute('verlogs'))
                $attributes =  array_merge($visible_attributes,$restricted_attributes);
            else
                $attributes =  $visible_attributes;

        ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => $attributes,
        ]) ?>
    </div>
    <div class="persona-view col-md-4"><br><br><br>
        <?= Html::img(['foto','username'=>$model->username],['width'=>'200px']) ?>
    </div>
</div>

<?php
if (Helper::checkRoute('verlogs')) {

    function wrapPjax($grid) {
    // fuente: https://forum.yiiframework.com/t/using-pjax-with-multiple-gridview-each-within-jquery-tabs/77625
        ob_start();
        Pjax::begin(['timeout' => 10000]);
        echo $grid;
        Pjax::end();
        return ob_get_clean();
    }



    $html_log_datos_filiatorios = GridView::widget([
        'dataProvider' => $log_datos_filiatorios,
        //'filterModel' => $searchModel,
        'columns' => [
            'date',
            'field_name',
            'old_value',
            'new_value',
            'user.username'
        ],
    ]);

    $html_log_mas_datos = GridView::widget([
        'dataProvider' => $log_mas_datos,
        //'filterModel' => $searchModel,
        'columns' => [
            'created_at:datetime',
            'campoAdicional.campo',
            'valor',
            [
                'attribute' => 'vigente',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->vigente) {
                        $icon_style = 'glyphicon-ok text-success';
                    } else {
                        $icon_style = 'glyphicon-remove text-danger';
                    }
                    return "<span class='glyphicon {$icon_style}'></span>";
                },
            ],
        ],
    ]);
     

    echo TabsX::widget([
        'items' => [
            [
                'label' => 'Historial de datos Filiatorios',
                'content' => wrapPjax($html_log_datos_filiatorios),
                'active' => true
            ],
            [
                'label' => 'Historial Otros Datos',
                'content' => wrapPjax($html_log_mas_datos),
            ],
            [
                'label' => 'Datos de la DGSI',
                //'url' => ['view-dgsi','id'=>'67778']
                'linkOptions'=>['data-url'=>Url::to(['view-dgsi','id'=>$model->id])]
            ],
        ],
    ]);
}
?>  
</div>
