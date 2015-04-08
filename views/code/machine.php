<?php
    $this->title = '生成机器码';
?>

<div class="bg-info">
    <div class="col-md-3 col-md-offset-1 bg-info">
        <h3>机器编号(<?= $model->id ?>)</h3>
        <h1><?= $model->serial_id ?></h1>
        <h3><?= Yii::$app->session['wechat']['name']?></h3>
        <p>销售、租售、维护</p>
        <p>彩色（黑色）复印件、一体机、打印机、传真机</p>
        <p>地址：广东省广州市天河区体育西路xx街yy号</p>
        <p>电话：020-22002133</p>
    </div>
    <div class="col-md-4">
        <h4>服务二维码</h4>
        <img src="<?= $qrcodeImgUrl?>" />
    </div>
</div>
