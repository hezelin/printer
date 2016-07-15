<?php
use yii\helpers\Url;
$this->title = '商品详情';
Yii::$app->params['layoutBottomHeight'] = 40;
use app\assets\AuicssAsset;
AuicssAsset::register($this);
?>

<?= \app\components\CarouselWidget::widget([
    'data'=>$model['cover_images'],
    'align'=>'center',
    'backgroundColor'=>'#fff'
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
    #pd_scar{height:100%;width:100%; display: none; top:0; left:0; position:fixed;z-index:599999;color:#202020;text-align:center;font-size:1.2em;}
    .pd_scar2{position:absolute;top:40%;width:80%;left:10%;background:#fff;z-index:3;border-radius:2px;}
    .pd_scar_a{border-bottom:1px #d7d7d7 solid;line-height:3em;margin-top:0.25em; padding: 10px 0;}
    .pd_scar_b a{display:block;width:50%;float:left;text-decoration:none;color:#202020; padding: 10px 0;}
    .pd_bg{float:left;height:100%;width:100%;background:#000;opacity:0.5;}

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
</style>

<div id="pd_scar">
    <div class="pd_scar2">
        <p class="pd_scar_a aui-border-0">商品已经成功加入购物车！</p>
        <p class="pd_scar_b aui-border-t">
            <a class="aui-text-danger" href="<?=Url::toRoute(['/shop/item/list','id'=>$id])?>">继续逛逛</a>
            <a class="aui-border-l aui-text-info" href="<?=Url::toRoute(['/shop/cart/list','id'=>$id])?>">去购物车结算</a>
        </p>
    </div>
    <div class="pd_bg pd-hide"></div>
</div>

<div class="aui-padded-10-0 aui-pd-b-del">
    <h3 class="aui-bg-white aui-padded-10 aui-pd-b-del"><?=$model['name']?></h3>
    <div class="aui-border-b aui-bg-white aui-padded-10 " style="padding-top:5px;">
        <div class="aui-pull-left" style="margin-top:3px;">价格：</div>
        <h4 class="aui-pull-left aui-text-danger"><?=$model['price']?>元</h4>
    </div>
</div>
<div class="aui-mg-t-10 aui-bg-white aui-padded-10 aui-border-b">
    <div class="aui-col-xs-12">
        <h5 class="aui-col-xs-4">类目</h5>
        <h5 class="aui-col-xs-8"><?=$model['category']?></h5>
    </div>
    <div class="aui-col-xs-12 aui-pd-t-10" style="padding-top:5px;">
        <?php if($model['else_attr']):?>
            <?php foreach($model['else_attr'] as $row):?>
            <div class="aui-col-xs-12">
                <h5 class="aui-col-xs-4"><?=$row['name']?></h5>
                <h5 class="aui-col-xs-8"><?=$row['value']?></h5>
            </div>
            <?php endforeach;?>
        <?php endif;?>
    </div>
</div>
<div class="aui-mg-t-10 aui-bg-white aui-padded-0-10 aui-border-b">
    <h5 class="aui-col-xs-12 aui-padded-10-0 aui-border-b" >
        <div class="aui-pull-left" style="border-left:3px solid #ff4400; height:22px; margin-right:8px;"></div>
        <div class="aui-pull-left">商品详情</div>
    </h5>
    <div class="aui-content">
        <?=$model['describe']?>
    </div>
</div>
<div class="aui-border-t aui-text-center aui-fixed-bottom" style="background: #fff; border:none; line-height:44px; height:44px; ">
    <a id="add_cart" class="aui-block aui-text-danger">
        <span class="aui-iconfont aui-icon-cart"></span>
        <span class="aui-inline-block " style="margin-left:5px; font-size:14px;">加入购物车</span>
    </a>
</div>

<script>
    <?php $this->beginBlock('JS_END') ?>

    var hasClick = 0;
    $(function(){
        $('#add_cart').click(function(){
            if(hasClick == 1) return false;
            $.post(
                '<?=Url::toRoute(['/shop/cart/add','id'=>$id])?>',
                {item_id:<?=$model['id']?>,openid:'<?=$openid;?>'},
                function(resp){
                    if(resp.status == 1)
                        $('#pd_scar').show();
                    else alert( resp.msg );
                    hasClick = 0;
                },'json'
            );
        });
        $('.pd-hide').click(function(){
            $('#pd_scar').hide();
        })
    });
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

\app\components\WxjsapiWidget::widget([
        'wx_id'=>$model['wx_id'],
        'apiList'=>['previewImage'],
        'jsReady'=>'
    document.querySelector("#carousel-iscroll").onclick = function () {
            wx.previewImage({
              current: "'.$model['big_cover_images'][0].'",
              urls: '.json_encode($model['big_cover_images']).'
            });
      };'
    ])

?>