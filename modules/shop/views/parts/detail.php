<?php
use yii\helpers\Url;
    $this->title = '商品详情';
Yii::$app->params['layoutBottomHeight'] = 40;
?>

<?= \app\components\CarouselWidget::widget([
    'data'=>$model['cover_images'],
    'align'=>'center',
    'backgroundColor'=>'#fff'
]) ?>


<div id="rent-detail">
    <hr class="de-line">
    <div class="de-row de-func">
        <?=$model['name']?>
    </div>
    <div class="de-row-b-2">
        <span class="de-label">价格</span>
        <em class="de-yan">¥</em>
        <span class="de-price"><?=$model['price']?></span>
    </div>
    <hr class="de-line-row">
    <ul class="de-box">
        <li class="de-row">
            <span class="de-label">市场价格</span>
            <span class="de-value">¥<?=$model['market_price']?>元</span>
        </li>
        <li class="de-row">
            <span class="de-label">类目</span>
            <span class="de-value"><?=$model['category']?></span>
        </li>
    </ul>

    <hr class="de-line-row">
    <?php if($model['else_attr']):?>
    <ul class="de-box">
        <li class="de-row">
        <?php foreach($model['else_attr'] as $row):?>
        <li class="de-row">
            <span class="de-label"><?=$row['name']?></span>
            <span class="de-value"><?=$row['value']?></span>
        </li>
        <?php endforeach;?>
    </ul>
    <hr class="de-line-row">
    <?php endif;?>
    <div class="de-row" style="padding-top: 5%;position: relative;width: 100%;">
        <?=$model['describe']?>
    </div>
    <div class="h-fixed-bottom">
        <a  href="<?=Url::toRoute(['/shop/parts/apply','id'=>$id,'item_id'=>$model['id']])?>">
            申请这个配件
        </a>
    </div>

</div>