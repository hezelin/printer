<?php
use yii\helpers\Url;
    $this->title = '机器页面';
?>


<div class="h-center-wrap">
    <!--<a class="h-link-minor" href="<?/*=Url::toRoute(['s/apply','id'=>$id,'mid'=>$mid])*/?>">
        维修进度
    </a>-->
    <?=$btnHtml;?>
    <a class="h-link" href="<?=Url::toRoute(['s/irecord','id'=>$id,'mid'=>$mid])?>">
        维修记录
    </a>
    <a class="h-link" href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        分享赚积分
    </a>
</div>

