<?php
use yii\helpers\Url;
    $this->title = '机器详情';
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
        <?=$model['function']?>
    </div>
    <div class="de-row-b-2">
        <span class="de-label">月租</span>
        <em class="de-yan">¥</em>
        <span class="de-price"><?=$model['lowest_expense']?></span>
    </div>
    <hr class="de-line-row">
    <ul class="de-box">
        <li class="de-row">
            <span class="de-label">黑白价格</span>
            <span class="de-value"><?=$model['black_white']?>元/张</span>
        </li>
        <?php if($model['is_color']==2):?>
        <li class="de-row">
            <span class="de-label">彩色价格</span>
            <span class="de-value"><?=$model['colours']?>元/张</span>
        </li>
        <?php endif;?>
        <li class="de-row">
            <span class="de-label">品牌</span>
            <span class="de-value"><?=$model['name']?></span>
        </li>
        <li class="de-row">
            <span class="de-label">型号</span>
            <span class="de-value"><?=$model['type']?></span>
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
    <div class="de-row" style="padding-top: 5%">
        <?=$model['describe']?>
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