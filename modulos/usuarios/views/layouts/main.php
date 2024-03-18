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
use app\models\Biblioteca;
use app\rbac\LibraryDbManager;


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
$libraries_menu[] = ['label' => '- Limpiar -','url' => ['/site/select-library','library_id'=>'']];


if (!Yii::$app->user->isGuest) {
    $user_id = Yii::$app->user->identity->id;
    $libraries_ids = LibraryDbManager::userLibraries($user_id);
   // var_dump($libraries_ids);exit;
}
else
    $libraries_ids = [];

if (count($libraries_ids) > 1) {

    foreach (Biblioteca::find()->where(['id'=>$libraries_ids])->all() as $biblioteca) {
        $libraries_menu[] = [
            'label'=>'<span class="glyphicon glyphicon-book" aria-hidden="true"></span> '.$biblioteca->nombre,
            'url'=>['/site/select-library','library_id'=>$biblioteca->id],
            'format'=>'html'
        ];
    }
}

$menuItems = array_merge(
        [
            [
                'label' => '<span class="glyphicon glyphicon-book" aria-hidden="true"></span> Biblioteca',
                'items'=>$libraries_menu,
                'visible'=>count($libraries_ids)>1
            ],
            ['label' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Lectores', 'url' => ['/persona/index']],
            
            ['label' => '<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Administrar', 'items'=>[
                    ['label' => '<span class="glyphicon glyphicon-book" aria-hidden="true"></span> Bibliotecas',
                            'url' => ['/biblioteca/index'],
                            'visible'=>Helper::checkRoute('/biblioteca/index')],

                    ['label' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Usuarios',
                            'url' => ['/user'],
                            'visible'=>Helper::checkRoute('/user/index')],

                    ['label' => '<span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Permisos',
                            'url' => ['/admin/assignment'],
                            'visible'=>Helper::checkRoute('/admin/assignment/index')],

                    ['label' => '<span class="glyphicon glyphicon-time" aria-hidden="true"></span> Logs Datos Filiatorios',
                            'url' => ['/persona-history/index'],
                            'visible'=>Helper::checkRoute('/persona-history/index')],
                ],
                'visible' =>Helper::checkRoute('/biblioteca/index') ||
                            Helper::checkRoute('/user/index') ||
                            Helper::checkRoute('/admin/assignment/index') ||
                            Helper::checkRoute('/persona-history/index')
            ],

            (Yii::$app->user->isGuest) ?
                    ['label' => 'Login', 'url' => ['/site/login']]
                : 
                    ['label' => Yii::$app->user->identity->username, 'items'=>[
                            [
                                'label' => '<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout',
                                'url' => ['/admin/user/logout'],
                                'linkOptions' => ['data-method' => 'post']
                            ],
                            ['label' => '<span class="glyphicon glyphicon-random" aria-hidden="true"></span> Cambiar contraseña', 'url' => ['/admin/user/change-password']],
                        ]
                     ]
        ]
);

//$menuItems = Helper::filter($menuItems);
// Esto lo podemos usar si definimos los menúes en index.php?r=admin%2Fmenu
//$menuItems = MenuHelper::getAssignedMenu(Yii::$app->user->id);

?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => ($library = Yii::$app->session->get('library'))?"<span style='font-size:1.5em;' class='text-primary'>$library->nombre</span>":Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
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
