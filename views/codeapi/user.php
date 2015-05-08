<?php
use yii\helpers\Url;
    $this->title = '机器页面';
?>


<div class="h-center-wrap">
    <a class="h-link" href="<?=Url::toRoute(['i/mapply','id'=>$id])?>">
        维修申请
    </a>
    <a class="h-link" href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        历史维修
    </a>
    <a class="h-link" href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        分享赚积分
    </a>
</div>

