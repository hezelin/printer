<?php

use yii\bootstrap\Alert;
use yii\helpers\Html;
$this->title = '状态提示';
?>

<?php
echo Alert::widget([
    'options' => [
        'class' => 'alert-danger'
    ],
    'body' => nl2br(Html::encode($tips)),
]);
?>