<?php

use yii\bootstrap\Alert;

$this->title = '添加机器';
$this->params['breadcrumbs'][] = ['label'=>'机器列表','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="alert alert-info" role="alert">
    1、 <span class="red">机器序列号</span>为唯一值（区分大小写），机器序列号数量与机器数量相等<br/>
    2、机器数量 大于 1,则需要输入多个编号用 <span class="red">逗号","</span> 隔开,例如： No1,No2,No3<br/>
    3、机器分类，出租指对外租赁的机器，销售指对外销售机器，维修是外部客户机器，由公司维修服务
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

