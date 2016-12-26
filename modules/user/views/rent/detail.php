<?php
use yii\helpers\Url;
    $this->title = '机器详情';
Yii::$app->params['layoutBottomHeight'] = 40;
use app\assets\AuicssAsset;
AuicssAsset::register($this);
?>

<?= \app\components\CarouselWidget::widget([
    'data'=>$model['images'],
    'align'=>'center',
    'backgroundColor'=>'#fff',
    'showNum' => true,
]) ?>
<style>
    body{
        background: #f8f8f8;
    }
    a:link{
        color: #262b31;
    }
    a:visited{
        color: #262b31;
    }


    /*aui*/
    .aui-padded-10-0{
        padding:10px 0;
        overflow:hidden;
    }
    .aui-padded-5-0{
        padding:5px 0;
        overflow:hidden;
    }
    .aui-bg-white{
        background: #fff;
    }

    .aui-pd-b-del{
        padding-bottom:0;
    }

    .aui-pd-t-10{
        padding-top:10px;
        overflow:hidden;
    }

    .aui-mg-t-10{
        margin-top:10px;
    }
    .aui-border-0{
        border:none 0;
    }

    /*flash*/
    #carousel-wrap{
        position: relative;
        overflow:hidden;
    }
    #carousel-wrap:after{
        display: block;
        content: '';
        position: absolute;
        z-index:5;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        -webkit-transform-origin: 0 0;
        -webkit-transform: scale(1);
        pointer-events: none;
        border-bottom: 1px solid #c8c7cc;

    }
    @media screen and (-webkit-min-device-pixel-ratio:1.5) {
        #carousel-wrap:after{
            right: -100%;
            bottom: -100%;
            -webkit-transform: scale(0.5);
        }
    }
    #carousel-nav{
        display: none !important;
    }
    .table_con h5{
        font-size: 15px;
        padding-bottom: 5px;
    }
    .table_con h5.aui-col-xs-8{
        color:#000;
    }
    .flash_circle{
        position: absolute;
    }
    .carousel-show-num{
        position: absolute;
        right:10px;
        bottom:10px;
        z-index: 200;
        border-radius:50%;
        background:rgba(0, 0, 0, 0.4);
        width:35px;
        height:35px;
        text-align: center;
        line-height: 35px;
        color:#fff;
    }
</style>

<div class="aui-pd-b-del">
    <div class="aui-border-b aui-bg-white aui-padded-10">
        <div class="aui-pull-left" style="margin-top:3px; font-size: 13px;">月租：</div>
        <h4 class="aui-pull-left aui-text-danger"><?=$model['lowest_expense']?>元</h4>
    </div>
</div>
<div class="table_con aui-bg-white aui-padded-10 aui-border-b">
    <div class="aui-col-xs-12 aui-pd-t-10" style="padding-top:5px;">
        <div class="aui-col-xs-12">
            <h5 class="aui-col-xs-4">黑白价格</h5>
            <h5 class="aui-col-xs-8"><?=\app\models\config\Tool::schemePrice($model['black_white'])?></h5>
        </div>
        <div class="aui-col-xs-12">
            <h5 class="aui-col-xs-4">彩色价格</h5>
            <h5 class="aui-col-xs-8"><?=\app\models\config\Tool::schemePrice($model['colours'])?></h5>
        </div>
        <?php if($model['contain_paper']):?>
        <div class="aui-col-xs-12">
            <h5 class="aui-col-xs-4">黑白张数</h5>
            <h5 class="aui-col-xs-8"><?=\app\models\config\Tool::paperNum($model['contain_paper'])?></h5>
        </div>
        <?php endif;?>
        <?php if($model['contain_colours']):?>
            <div class="aui-col-xs-12">
                <h5 class="aui-col-xs-4">彩色张数</h5>
                <h5 class="aui-col-xs-8"><?=\app\models\config\Tool::paperNum($model['contain_colours'])?></h5>
            </div>
        <?php endif;?>
        <div class="aui-col-xs-12">
            <h5 class="aui-col-xs-4">品牌</h5>
            <h5 class="aui-col-xs-8"><?=\app\models\config\ConfigScheme::brand($model['brand'])?></h5>
        </div>
        <div class="aui-col-xs-12">
            <h5 class="aui-col-xs-4">型号</h5>
            <h5 class="aui-col-xs-8"><?=$model['model']?></h5>
        </div>
    </div>
</div>

<div class="aui-bg-white aui-padded-0-10 aui-border-b">
    <h5 class="aui-col-xs-12 aui-padded-10-0 aui-border-b" >
        <div class="aui-pull-left" style="border-left:3px solid #3498db; height:22px; margin-right:8px;"></div>
        <div class="aui-pull-left">商品详情</div>
    </h5>
    <div class="aui-content" style="padding:10px 0;">
        <?=$model['describe']?>
    </div>
</div>

<div class="aui-border-t aui-text-center aui-fixed-bottom" style="background: #fff; border:0 none; line-height:44px; height:44px; ">
    <?php if( Yii::$app->request->get('from') == 'machine'):?>
    <a id="add_cart" class="aui-block aui-text-danger" href="<?=url::toRoute(['/maintain/fault/apply','id'=>$id,'mid'=>$model['id']])?>">
        <span class="aui-inline-block aui-text-info" style="margin-left:5px; font-size:16px;">维修申请</span>
    </a>
    <?php elseif( Yii::$app->request->get('from') == 'scan'):?>
    <?php else:?>
    <a id="add_cart" class="aui-block aui-text-danger" href="<?=url::toRoute(['/user/rent/apply','id'=>$id,'mid'=>$model['id']])?>">
        <span class="aui-inline-block aui-text-info" style="margin-left:5px; font-size:16px;">租借机器</span>
    </a>
    <?php endif;?>
</div>
