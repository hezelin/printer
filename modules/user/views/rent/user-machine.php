<?php
use yii\helpers\Url;
    $this->title = '机器详情';
Yii::$app->params['layoutBottomHeight'] = 40;
?>

<?= \app\components\CarouselWidget::widget([
    'data'=>$model['images'],
    'align'=>'center',
    'backgroundColor'=>'#fff'
]) ?>


<div id="rent-detail">
    <hr class="de-line">
    <div class="de-row" style="padding-top: 10px;">
        <span class="de-label">月租</span>
        <em class="de-yan">¥</em>
        <span class="de-price"><?=$model['monthly_rent']?></span>
    </div>
    <hr class="de-line-row">
    <ul class="de-box">
        <li class="de-row">
            <span class="de-label">黑白价格</span>
            <span class="de-value"><?=\app\models\config\Tool::schemePrice($model['black_white'])?></span>
        </li>
        <?php if($model['colours']):?>
        <li class="de-row">
            <span class="de-label">彩色价格</span>
            <span class="de-value"><?=\app\models\config\Tool::schemePrice($model['colours'])?></span>
        </li>
        <?php endif;?>
        <li class="de-row">
            <span class="de-label">品牌</span>
            <span class="de-value"><?=$model['brand_name']?></span>
        </li>
        <li class="de-row">
            <span class="de-label">型号</span>
            <span class="de-value"><?=$model['model_name']?></span>
        </li>
        <?php if($model['series_id']):?>
        <li class="de-row">
            <span class="de-label">客户编号</span>
            <span class="de-value"><?=$model['series_id']?></span>
        </li>
        <?php endif;?>
        <?php if($model['come_from']!=1):?>
            <li class="de-row">
                <span class="de-label">租借时间</span>
                <span class="de-value"><?=date('Y-m-d H:i',$model['add_time'])?>元/张</span>
            </li>
        <?php endif;?>
    </ul>

    <hr class="de-line-row">

    <ul class="de-box">
        <li class="de-row">
            <span class="de-label">用户</span>
            <span class="de-value"><?=$model['user']?></span>
        </li>
        <li class="de-row">
            <span class="de-label">地址</span>
            <span class="de-value"><?=$model['address']?></span>
        </li>
    </ul>

    <hr class="de-line-row">
    <div class="de-row" style="padding-top: 5%">

    </div>
    <?php if( Yii::$app->request->get('from') == 'machine'):?>
        <div class="h-fixed-bottom">
            <a  href="<?=url::toRoute(['/maintain/fault/apply','id'=>$id,'mid'=>$model['id']])?>">
                维修申请
            </a>
        </div>
    <?php elseif( Yii::$app->request->get('from') == 'scan'):?>

    <?php else:?>
        <div class="h-fixed-bottom">
            <a href="<?=url::toRoute(['/user/rent/apply','id'=>$id,'mid'=>$model['id']])?>">
                租借机器
            </a>
        </div>
    <?php endif;?>
</div>