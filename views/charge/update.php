<?php
$this->title = '修改录入';
?>

<ul class="list-group">
    <li class="list-group-item">用户：<?=$rent['name'],'，地址：',$rent['address']?></li>
    <li class="list-group-item">
        租金：最低消费<?=$rent['monthly_rent']?>元，付款周期：<?=$rent['rent_period']?>月，黑白 <?=$rent['black_white']?>元/每张
        <?php
        if($rent['colours'])
            echo '，彩色：',$rent['colours'],'元/每张';
        ?>
    </li>
    <li class="list-group-item">
        上次读数：<?php
        if($lastCharge){
            echo '黑白：',$lastCharge['black_white'],'张';
            if($rent['colours'])
                echo '，彩色：',$lastCharge['colour'],'张';
            echo '，租金：',$lastCharge['total_money'],'元，超出金额：',$lastCharge['exceed_money'],'元，收取时间：',date('Y-m-d H:i',$lastCharge['add_time']);
        }else
            echo '无';
        ?>
    </li>
</ul>

<?= $this->render('_form', [
    'model' => $model,
    'hasColour'=>$rent['colours'],
    'rent'=>$rent,
    'lastCharge'=>$lastCharge
]) ?>

