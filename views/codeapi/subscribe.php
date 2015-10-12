<?php
use yii\helpers\Url;
    $this->title = '关注公众号';
?>
<style>
    .tmp-tips{  padding:20px 15px; font-size: 16px;}
    .tmp-p{font-size: 14px; padding:15px;}
    #qrcode-img{ width: 100%;}
</style>

<div class="tmp-tips">
    您还没有关注公众号，无法发起维修申请！
</div>
<p class="tmp-p">长按图片识别二维码关注</p>
<p class="tmp-p">关注成功后重新扫描机器二维码</p>
<img id="qrcode-img" src="http://open.weixin.qq.com/qr/code/?username=<?=$wx_num?>" />

