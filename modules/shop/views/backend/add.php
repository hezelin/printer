<?php

use yii\bootstrap\Alert;

$this->title = '添加商品';
$this->params['breadcrumbs'][] = $this->title;

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
        'category' => \app\modules\shop\models\Shop::getCategory()
    ]) ?>

