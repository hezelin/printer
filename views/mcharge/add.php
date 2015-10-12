<?php
    $this->title = '添加收租';
?>

<div id="list-wrap">

<div class="list-head">用户信息 <em class="close-em">展开</em></div>
<ul class="list-group">
    <li class="list-group-item"><span class="list-label">用户：</span><span class="list-text"><?=$rent['name']?></span></li>
    <li class="list-group-item"><span class="list-label">地址：</span><span class="list-text"><?=$rent['address']?></span></li>
    <li class="list-group-item">
        <span class="list-label">租金：</span><span class="list-text">最低消费<?=$rent['monthly_rent']?>元，付款周期：<?=$rent['rent_period']?>个月，黑白 <?=$rent['black_white']?>元/张
            <?php
            if($rent['colours'])
                echo '，彩色：',$rent['colours'],'元/张';
            ?>
        </span>
    </li>
    <li class="list-group-item">
        <span class="list-label">上次读数：</span><span class="list-text"><?php
            if($lastCharge){
                echo '黑白：',$lastCharge['black_white'],'张';
                if($rent['colours'])
                    echo '，彩色：',$lastCharge['colour'],'张';
                echo '，租金：',$lastCharge['total_money'],'元，超出金额：',$lastCharge['exceed_money'],'元，收取时间：',date('Y-m-d H:i',$lastCharge['add_time']);
            }else
                echo '无';
            ?>
        </span>
    </li>
</ul>
</div>
<p style="height: 50px; line-height: 50px; display: block">&nbsp;</p>
<?= $this->render('_form', [
    'next_rent' => $next_rent,
    'wx_id'=>$wx_id,
    'hasColour'=>$rent['colours'],
    'rent'=>$rent,
    'lastCharge'=>$lastCharge,
    'openid'=>$openid
]) ?>
