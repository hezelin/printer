<?php
use yii\helpers\Url;

$this->title = '关注公众号';

?>
<style>
    .share-text, .share-text li{list-style: none; margin: 0; padding: 0;}
    .share-title{ height: 50px; line-height: 50px; padding-left: 15px; font-size: 16px;}
    .share-text {
        border: 1px dotted #6167ed;
        background-color: #d2d9ee;
        padding: 15px;
        line-height: 24px;
        margin: 0 15px;
        border-radius: 4px;
    }
</style>

<img width="100%" src="http://open.weixin.qq.com/qr/code/?username=<?=$wx_num?>" />


<div class="share-title">分享提示</div>
<ul class="share-text">
    <li>1、长按图片识别二维码，可关注公众号</li>
</ul>
