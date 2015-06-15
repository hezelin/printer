<?php

use yii\bootstrap\Alert;

$this->title = '添加机器';
?>


<div class="alert alert-info" role="alert">
    1、 <span class="red">机器系列号</span>为唯一值（区分大小写），机器系列号数量与机器数量相等<br/>
    2、机器数量 大于 1,则需要输入多个编号用 <span class="red">逗号","</span> 隔开,例如： No1,No2,No3<br/>
    3、自家机器默认为 是，否表示外面用户的机器（没有租借的关系，便于管理，便于维修）,建议编号加上前缀
</div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

