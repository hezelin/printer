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


    <?php
    if(isset($btnText,$btnUrl) && $btnText && $btnUrl){

        echo Html::a($btnText,$btnUrl,['class'=>'btn btn-info']);
    }
    if(isset($btnText2,$btnUrl2) && $btnText2 && $btnUrl2){

        echo Html::a($btnText2,$btnUrl2,['class'=>'btn btn-default','style'=>'margin-left:15px;']);
    }
    ?>
