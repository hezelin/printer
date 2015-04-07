<?php

use yii\bootstrap\Alert;

$this->title = '添加机器';
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

<div class="alert alert-info" role="alert">
    1、 <span class="red">机器编号</span>为唯一值，不填则系统自动生成<br/>
    2、数量 大于 1,则生成<span class="red">多台</span> 相同参数，连续编号的机器。如果预设编号则生成前缀的连续编号。
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

