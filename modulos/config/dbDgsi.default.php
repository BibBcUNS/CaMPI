<?php
/*
SELECT * FROM externas.ifx_vw834_alumnos_bib; -- Recupera en el momento los datos de la vista informix vw834_alumnos_bib de sgr04
SELECT * FROM public.vw_834_alumnos_bib; -- Tabla creada y actualizada por un script ejecutado por el cron de postgres en sg-ingresos.un.edu.ar

*/
return [

    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=<HOSTNAME OR IP>;port=<PORT>;dbname=<DB_NAME>',//pgsql, sqlsrv, etc
    'username' => 'xxxxxxx',
    'password' => 'xxxxxxx',
    'charset' => 'utf8',
    /*'schemaMap' => [
        'pgsql'=> [
            'class'=>'yii\db\pgsql\Schema',
            'defaultSchema' => 'public',
        ],
    ],*/
];
