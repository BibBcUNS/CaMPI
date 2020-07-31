<?php

use app\models\Biblioteca;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Biblioteca</h1>

        <?php foreach (Biblioteca::find()->all() as $Biblioteca):  ?>
            <a class="btn btn-lg btn-primary" href="<?=Yii::$app->urlManager->createUrl(['/create','id'=>$model->id])?>">Get started with Yii</a>
        <?php endforeach; ?>
    </div>

    <div class="body-content">
    </div>
</div>
