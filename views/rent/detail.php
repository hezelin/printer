<?php
use yii\helpers\Url;
    $this->title = '机器详情';
?>

<div id="rent-detail">
    <div class="de-cover">
        <img class="de-cover-img" src="<?=$model->cover?>" />
    </div>
    <hr class="de-line">
    <div class="de-row de-func">
        <?=$model->function?>
    </div>
    <div class="de-row-b-2">
        <span class="de-label">月租</span>
        <em class="de-yan">¥</em>
        <span class="de-price"><?=$model->monthly_rent?></span>
    </div>
    <hr class="de-line-row">
    <ul class="de-box">
        <li class="de-row">
            <span class="de-label">品牌</span>
            <span class="de-value"><?=$model->brand?></span>
        </li>
        <li class="de-row">
            <span class="de-label">型号</span>
            <span class="de-value"><?=$model->type?></span>
        </li>
    </ul>

    <hr class="de-line-row">
    <?php if($model->else_attr && $attr = json_decode($model->else_attr,true)):?>
    <ul class="de-box">
        <li class="de-row">
        <?php foreach($attr as $row):?>
        <li class="de-row">
            <span class="de-label"><?=$row['name']?></span>
            <span class="de-value"><?=$row['value']?></span>
        </li>
        <?php endforeach;?>
    </ul>
    <?php endif;?>
    <p style="height: 50px;">&nbsp;</p>
    <div style="clear:both; display: none;"></div>

    <?php if( Yii::$app->request->get('from') == 'machine') :?>
    <a class="de-fiexd-bottom" href="<?=Url::toRoute(['s/apply','id'=>$model->wx_id,'mid'=>$model->id])?>">
        维修申请
    </a>
    <?php else:?>
    <a class="de-fiexd-bottom" href="<?=Url::toRoute(['rent/apply','id'=>$model->wx_id,'mid'=>$model->id])?>">
        租借机器
    </a>
    <?php endif;?>
</div>