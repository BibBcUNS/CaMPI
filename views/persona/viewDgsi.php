<?php
use yii\widgets\DetailView;
use yii\grid\GridView;
?>
<div class="row">
    <div class="persona-view col-md-12">
        <?php 
            echo DetailView::widget([
                'model' => $datos_dgsi,
                //'filterModel' => $searchModel,
                'attributes' => [
                    ['attribute'=>'legajo',              'visible'=>!empty($datos_dgsi['legajo'])],
                    ['attribute'=>'nro_inscripcion',     'visible'=>!empty($datos_dgsi['nro_inscripcion'])],
                    [
                	'attribute'=>'carreras',
                	'format'=>'raw',
                	'value'=>function ($model) use ($carreras) {
                	    $lista_carreras = '';
                	    foreach ($carreras as $carrera) {
                		$lista_carreras .= "<span class='col-md-1'>{$carrera['carrera']}:</span>".(($carrera['regular']=='S ')?'Regular':'No regular').'<br>';
                	    }
                	    return $lista_carreras;
                	},
                	'visible'=>!empty($datos_dgsi['carrera'])
                    ],
                    ['attribute'=>'calidad',             'visible'=>!empty($datos_dgsi['calidad'])],
                    ['attribute'=>'regular',             'visible'=>!empty($datos_dgsi['regular'])],
                    ['attribute'=>'tipo_documento',      'visible'=>!empty($datos_dgsi['tipo_documento'])],
                    ['attribute'=>'nro_documento',       'visible'=>!empty($datos_dgsi['nro_documento'])],
                    ['attribute'=>'apellido',            'visible'=>!empty($datos_dgsi['apellido'])],
                    ['attribute'=>'nombres',             'visible'=>!empty($datos_dgsi['nombres'])],
                    ['attribute'=>'fecha_nacimiento',    'visible'=>!empty($datos_dgsi['fecha_nacimiento'])],
                    ['attribute'=>'e_mail',              'visible'=>!empty($datos_dgsi['e_mail'])],
                    ['attribute'=>'calle_per_lect',      'visible'=>!empty($datos_dgsi['calle_per_lect'])],
                    ['attribute'=>'numero_per_lect',     'visible'=>!empty($datos_dgsi['numero_per_lect'])],
                    ['attribute'=>'piso_per_lect',       'visible'=>!empty($datos_dgsi['piso_per_lect'])],
                    ['attribute'=>'unidad_per_lect',     'visible'=>!empty($datos_dgsi['unidad_per_lect'])],
                    ['attribute'=>'loc_periodo_lectivo', 'visible'=>!empty($datos_dgsi['loc_periodo_lectivo'])],
                    ['attribute'=>'cp_per_lect',         'visible'=>!empty($datos_dgsi['cp_per_lect'])],
                    ['attribute'=>'te_per_lect',         'visible'=>!empty($datos_dgsi['te_per_lect'])],
                    ['attribute'=>'dpto_per_lect',       'visible'=>!empty($datos_dgsi['dpto_per_lect'])],
                    ['attribute'=>'calle_proc',          'visible'=>!empty($datos_dgsi['calle_proc'])],
                    ['attribute'=>'numero_proc',         'visible'=>!empty($datos_dgsi['numero_proc'])],
                    ['attribute'=>'piso_proc',           'visible'=>!empty($datos_dgsi['piso_proc'])],
                    ['attribute'=>'unidad_proc',         'visible'=>!empty($datos_dgsi['unidad_proc'])],
                    ['attribute'=>'loc_proc',            'visible'=>!empty($datos_dgsi['loc_proc'])],
                    ['attribute'=>'cp_proc',             'visible'=>!empty($datos_dgsi['cp_proc'])],
                    ['attribute'=>'te_proc',             'visible'=>!empty($datos_dgsi['te_proc'])],
                    ['attribute'=>'dpto_proc',           'visible'=>!empty($datos_dgsi['dpto_proc'])],
                ],
            ]);
        ?>
    </div>
</div>
