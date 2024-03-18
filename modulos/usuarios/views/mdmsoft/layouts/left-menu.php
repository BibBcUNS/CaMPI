<?php

use yii\helpers\Html;
use mdm\admin\components\Helper;

/* @var $this \yii\web\View */
/* @var $content string */

$controller = $this->context;
$menus = $controller->module->menus;
$route = $controller->route;
foreach ($menus as $i => $menu) {
    $menus[$i]['active'] = strpos($route, trim($menu['url'][0], '/')) === 0;
}
$this->params['nav-items'] = $menus;

$library_name = ($library=Yii::$app->session->get('library'))?"$library->nombre":Yii::$app->name;
?>
<?php $this->beginContent($controller->module->mainLayout) ?>
<div class="row">
    <div class="col-md-12 text-center">
            <span style="font-size: 3em"><?=$library_name?></span>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div id="manager-menu" class="list-group">
            <?php
            foreach ($menus as $menu) {
                if(Helper::checkRoute($menu['url'][0].'/index')){
                    $label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
                        Html::tag('span', Html::encode($menu['label']), []);
                    $active = $menu['active'] ? ' active' : '';
                    echo Html::a($label, $menu['url'], [
                        'class' => 'list-group-item' . $active,
                    ]);
                }
            }
            ?>
        </div>
    </div>
    <div class="col-sm-9">
        <?= $content ?>
    </div>
</div>
<?php
list(, $url) = Yii::$app->assetManager->publish('@mdm/admin/assets');
$this->registerCssFile($url . '/list-item.css');
?>

<?php $this->endContent(); ?>
