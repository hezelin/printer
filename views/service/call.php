<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = '电话维修';
$this->params['breadcrumbs'][]= $this->title;
?>

<div class="alert alert-success" role="alert">
    <p>用户没有机器编号，无需填写，系统会自动新建机器</p><br/>
    <?php
    echo Html::beginForm('/service/new-call','get',['class'=>'form-inline']);
    echo Html::textInput('machine_id','',['class'=>'form-control','placeholder'=>'输入机器编号']);
    echo Html::submitButton('点击报修',['class'=>'btn btn-info','style'=>'margin-left:15px']);
    echo Html::endForm();
    ?>
</div>