<?php
use yii\bootstrap\Alert;
use yii\helpers\Html;
$this->title = '状态提示';

/*
 * 参数  $tips ，提示文件
 * $btnText 按钮文字
 * $btnUrl 按钮链接
 */
?>

<?php
echo Alert::widget([
    'options' => [
        'class' => 'alert-info'
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

