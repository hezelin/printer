<?php
use yii\helpers\Url;
    $this->title = '机器页面';
?>

<style>
    #simple-wrap{
        margin: 15% auto 10%;
        width: 80%;
    }
    #simple-wrap a{
        height: auto;
        color: #ffffff;
        padding: 10px 0;
        display: block;
        font-size: 20px;
        background-color: #76B764;
        border-radius: 4px;
        outline: none;
        text-decoration: none;
        width: 100%;
        margin: 30px 0;
        text-align: center;
    }
</style>


<div id="simple-wrap">
    <a href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        维修申请
    </a>
    <a href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        历史维修
    </a>
    <a href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        分享赚积分
    </a>
</div>

