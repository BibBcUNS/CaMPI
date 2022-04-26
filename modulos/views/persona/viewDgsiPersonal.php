<?php
use yii\widgets\DetailView;
use yii\grid\GridView;
?>

<style>
    .cargos td, .cargos th {
        padding:5px;
        border: 1px dashed gray;
    }
    /*.cargos {
        border:1px solid
    }*/
</style>
<div class="row">
    <div class="persona-view col-md-12">
        <?php
            echo DetailView::widget([
                'model' => $datos_dgsi,
                //'filterModel' => $searchModel,
                'attributes' => [
                    [
                        'attribute'     =>'legajo',
                        'label'         =>'Legajo',
                        'visible'       =>!empty($datos_dgsi['legajo'])],
                    [
                        'attribute'     =>'apellido',
                        'label'         =>'Apellido',
                        'visible'       =>!empty($datos_dgsi['apellido'])],
                    [
                        'attribute'     =>'nombre',
                        'label'         =>'Nombre',
                        'visible'       =>!empty($datos_dgsi['nombre'])],
                    [
                        'attribute'     =>'documento_tipo',
                        'label'         =>'Tipo Doc.',
                        'visible'       =>!empty($datos_dgsi['documento_tipo'])],
                    [
                        'attribute'     =>'documento_numero',
                        'label'         =>'Nro. Doc.',
                        'visible'       =>!empty($datos_dgsi['documento_numero'])],
                    [
                        'attribute'     =>'calle',
                        'label'         =>'Calle',
                        'visible'       =>!empty($datos_dgsi['calle'])],
                    [
                        'attribute'     =>'numero',
                        'label'         =>'Número',
                        'visible'       =>!empty($datos_dgsi['numero'])],
                    [
                        'attribute'     =>'piso',
                        'label'         =>'Piso',
                        'visible'       =>!empty($datos_dgsi['piso'])],
                    [
                        'attribute'     =>'dpto_oficina',
                        'label'         =>'Departamento/Oficina',
                        'visible'       =>!empty($datos_dgsi['dpto_oficina'])],
                    [
                        'attribute'     =>'codigo_postal',
                        'label'         =>'C.P.',
                        'visible'       =>!empty($datos_dgsi['codigo_postal'])],
                    [
                        'attribute'     =>'telefono',
                        'label'         =>'Teléfono',
                        'visible'       =>!empty($datos_dgsi['telefono'])],
                    [
                        'attribute'     =>'Celular',
                        'label'         =>'telefono_celular',
                        'visible'       =>!empty($datos_dgsi['telefono_celular'])],
                    [
                        'attribute'     =>'correo_electronico',
                        'label'         =>'Email',
                        'visible'       =>!empty($datos_dgsi['correo_electronico'])],
                    [
                        'attribute'=>'Cargos',
                        'format'=>'raw',
                        'value'=>function ($datos_dgsi) {
                            $lista_cargos = "<tr><th>Dependencia</th><th>Escalafón</th><th>Cargo</th><th>Dedicación</th></tr>";
                            foreach ($datos_dgsi['cargos'] as $cargo) {
                                $lista_cargos .= "<tr>".
                                "<td>{$cargo['dependencia_descripcion']}</td>".
                                "<td>{$cargo['escalafon_descripcion']}</td>".
                                "<td>{$cargo['categoria_descripcion']}</td>".
                                "<td>".(($cargo['dedicacion'] !== '')?$cargo['dedicacion']:'')."</td>".
                                "<tr>";
                            }
                            return  "<table class='cargos'>".$lista_cargos."</table>";
                        },
                        'visible'=>!empty($datos_dgsi['cargos'])
                    ],
                ],
            ]);
        ?>
    </div>
</div>
