<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use mdm\admin\components\MenuHelper;
use mdm\admin\components\Helper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
$menuItems = array_merge(
    //Helper::filter(
        [
            //['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Personas', 'url' => ['/persona/index']],
            ['label' => 'Bibliotecas', 'url' => ['/biblioteca/index']],
            ['label' => 'Logs Datos Filiatorios', 'url' => ['/persona-history/index']],
            ['label' => 'Administracion', 'items'=>[
                    ['label' => 'Permisos', 'url' => ['/admin/user']],
                    ['label' => 'Nuevo Usuario', 'url' => ['/admin/user/signup']],
                ]
             ],
            (Yii::$app->user->isGuest) ?
                    ['label' => 'Login', 'url' => ['/admin/user/login']]
                : 
                    ['label' => Yii::$app->user->identity->username, 'items'=>[
                            [
                                'label' => 'Logout',
                                'url' => ['admin/user/logout'],
                                'linkOptions' => ['data-method' => 'post']
                            ],
                            ['label' => 'Cambiar contraseña', 'url' => ['/admin/user/change-password']],
                        ]
                     ]
        ]
    //)
);
?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        // Helper::filter musetra solamente los menúes disponibles para el usuario específico.
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
