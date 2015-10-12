<?php
use yii\helpers\Url;

$this->title = '我的积分';

?>
<p style="width: 100%; height: 15%; float:left;">&nbsp;</p>
<style>
    #top{ height: 220px; background-color: #3399CC; color: #FFFFFF; text-align: left;
        position: relative; margin-bottom: 15px;
    }
    .score-title{ height: 20px; line-height: 20px; font-size: 14px; padding-left: 15px;}
    .top-content{ height: 200px; line-height:200px;font-size: 60px;  text-align: center; font-family: "arial", "微软雅黑";}
</style>

<div id="top">
    <p class="score-title">累计积分</p>
    <h1 class="top-content"><?=$totalScore?></h1>
</div>

