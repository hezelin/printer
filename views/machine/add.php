<?php

use yii\bootstrap\Alert;

$this->title = '添加机器';
$this->params['breadcrumbs'][] = ['label'=>'机器列表','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="alert alert-info" role="alert">
    1、机器分类，出租指对外租赁的机器，销售指对外销售机器，维修是外部客户机器，由公司维修服务<br/>
    2、虚拟机器是不存在的机器提前打印出机器二维码，以后完善资料
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

