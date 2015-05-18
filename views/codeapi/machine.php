<?php
use yii\helpers\Url;
$this->title = '机器页面';
?>


<div class="h-center-wrap">
    <a class="h-link-minor" href="<?=Url::toRoute(['i/mapply','id'=>$id])?>">
        维修状态
    </a>
    <a class="h-link" href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        故障信息
    </a>
    <a class="h-link" href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        维修记录
    </a>
</div>

