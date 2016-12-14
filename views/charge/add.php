<?php
    $this->title = '添加收租';
$this->params['breadcrumbs'][] = $this->title;
?>

<ul class="list-group">
    <li class="list-group-item">用户：<?=$rent['name'],'，地址：',$rent['address']?></li>
    <li class="list-group-item">
    租金：最低消费<?=$rent['monthly_rent']?>元(包含 <?=$rent['contain_paper']? :0?> 张黑白, 包含 <?=$rent['contain_colours']? :0?> 张彩色)，收租周期：<?=$rent['rent_period']?>个月，黑白 <?=$rent['black_white']?>元/张
    <?php
        if($rent['colours'])
            echo '，彩色：',$rent['colours'],'元/张';
    ?>
    </li>
    <li class="list-group-item">
    <?php
        if($lastCharge){
            echo '上次读数：黑白：',$lastCharge['black_white'],'张';
            if($rent['colours'])
                echo '，彩色：',$lastCharge['colour'],'张';
            echo '，租金：',$lastCharge['total_money'],'元，超出金额：',$lastCharge['exceed_money'],'元，收取时间：',date('Y-m-d H:i',$lastCharge['add_time']);
        }else{
            echo '初始读数：';
            echo '黑白：',$rent['black_amount'],'张';
            if( $rent['colours_amount']>0 )
                echo '，彩色：',$rent['colours_amount'],'张';
        }
    ?>
    </li>
</ul>

<?= $this->render('_form', [
    'model' => $model,
    'hasColour'=>$rent['colours'],
    'rent'=>$rent,
    'lastCharge'=>$lastCharge
]) ?>

