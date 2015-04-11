<?php
$this->title = '积分二维码';
?>

<div class="col-md-3 col-md-offset-1 bg-info">
    <h3>用户扫描积分之后</h3>
    <p>后台可看到用户申请的获取积分</p>
    <p>收银的时候，一起为用户赠送积分</p>
    <h1>积分二维码  ></h1>
</div>
<div class="col-md-4">
    <div class="qrcode-bg">
        <img src="<?= $qrcodeImgUrl?>" width="300" height="300" />
    </div>
</div>
