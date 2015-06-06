<?php

use yii\bootstrap\Alert;

$this->title = '修改商品';
?>

<?php
if( Yii::$app->session->hasFlash('error') )
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger',
        ],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
?>


    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category
    ]) ?>

