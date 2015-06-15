<?php
$this->title = '打印机器码';
?>

<div class="container-fluid">
    <?php foreach($list as $index => $one):?>
<!--        --><?php //if($index%2 === 0):?>
            <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
<!--        --><?php //endif;?>

        <div class="col-md-3 bg-info">
            <h3>机器编号</h3>
            <h1><?= $one['serial']?></h1>
            <h3><?= Yii::$app->session['wechat']['name']?></h3>
            <!--<p>销售、租售、维护</p>
            <p>彩色（黑色）复印件、一体机、打印机、传真机</p>
            <p>地址：广东省广州市天河区体育西路xx街yy号</p>
            <p>电话：020-22002133</p>-->
        </div>
        <div class="col-md-3">
            <h4>服务二维码</h4>
            <div class="qrcode-bg">
                <img src="<?= $one['url']?>" width="300" height="300" />
            </div>
        </div>
<!--        --><?php //if($index%2 === 1):?>
            </div>
<!--        --><?php //endif;?>
    <?php endforeach;?>
</div>