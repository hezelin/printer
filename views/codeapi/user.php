<?php
use yii\helpers\Url;
    $this->title = '机器页面';
?>


<div class="h-center-wrap">
    <!--<a class="h-link-minor" href="<?/*=url::toRoute(['/maintain/fault/apply','id'=>$id,'mid'=>$mid])*/?>">
        维修进度
    </a>-->
    <?=$btnHtml;?>
    <a class="h-link" href="<?=Url::toRoute(['/user/i/service','id'=>$id,'mid'=>$mid])?>">
        维修记录
    </a>
    <a class="h-link" href="<?=Url::toRoute(['share/scheme','id'=>$id])?>">
        分享赚积分
    </a>
</div>

