<?php

use yii\bootstrap\Alert;

$this->title = '添加机器';
?>


<div class="alert alert-info" role="alert">
    1、 <span class="red">机器系列号</span>为唯一值（区分大小写），机器系列号数量与机器数量相等<br/>
    2、机器数量 大于 1,则需要输入多个编号用 <span class="red">逗号","</span> 隔开,例如： No1,No2,No3
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

