<?php
use yii\helpers\Url;
$this->title = '扫描配件';
?>
<div class="h-center-wrap">
    <a class="h-link" href="<?=Url::toRoute(['/shop/parts/log','parts_id'=>$parts_id])?>">
        查看记录
    </a>
    <a class="h-link" href="<?=Url::toRoute(['/shop/parts/select','id'=>$id,'parts_id'=>$parts_id])?>">
        绑定维修
    </a>
    <a class="h-link" href="<?=Url::toRoute(['/shop/parts/remark','id'=>$id,'parts_id'=>$parts_id])?>">
        备注消息
    </a>
</div>