<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbDgsi = require __DIR__ . '/dbDgsi.php';

$config = [
    'defaultRoute' => 'persona/index',
	//'defaultRoute' => 'site/select-library',
	'name' => 'Administración de Usuarios',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'es',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' =>'@app/views/mdmsoft/layouts/left-menu',// 'left-menu',
            'controllerMap' => [
                'user' => 'app\controllers\UserController'
            ],
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/select-library',
            'admin/user/login',
            'admin/user/logout',
            'services/profile',
            //'admin/user/signup',
            //'admin/*',
            //'persona/*',
            //'some-controller/some-action',*/
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],
    'components' => [
        'security' => ['class' => 'app\models\base\Security'],
        'formatter' => [
           'datetimeFormat'=>'php:d M Y - H:i:s'
        ],
    	'authManager' => [
            //'class' => 'yii\rbac\DbManager', // use 'yii\rbac\PhpManager' or yii\rbac\DbManager'
            'class' => 'app\rbac\LibraryDbManager',
            'defaultRoles' => ['operador'],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@mdm/admin/views/assignment' => '@app/views/mdmsoft/assignment',  // mapping for override the views 
                    '@mdm/admin/views/user' => '@app/views/mdmsoft/user',  // mapping for override the views 
                    '@mdm/admin/views/layouts' => '@app/views/layouts',  // mapping for override the views 
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gLoX8eZjK3Jon3iKoY5g56zM_VAc0vEz',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            //'enableAutoLogin' => true,
            //'identityClass' => 'mdm\admin\models\User',
            'loginUrl' => ['site/login'],
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'dbDgsi' => $dbDgsi,
        'urlManager' => [
            //'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'services'],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
