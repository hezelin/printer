<?php

use yii\helpers\Html;

$this->title = '出错';
?>

<div class="aui-tips aui-tips-danger" id="tips-1" style="margin-bottom: 50px;">
    <div class="aui-tips-content" >
        <i class="aui-iconfont aui-icon-warnfill"></i>
        <?= nl2br(Html::encode($message)) ?>
    </div>
</div>
