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
<style>
    a:link{
        color: #262b31;
    }
    a:visited{
        color: #262b31;
    }
    #pd_scar{height:100%;width:100%; display: none; top:0; left:0; position:fixed;z-index:599999;color:#202020;text-align:center;font-size:1em;}
    .pd_scar2{position:absolute;top:40%;width:80%;left:10%;background:#fff;z-index:3;border-radius:6px;}
    .pd_scar_a{border-bottom:1px #d7d7d7 solid;line-height:3em;margin-top:0.25em;}
    .pd_scar_b a{display:block;width:50%;float:left;line-height:3em;text-decoration:none;color:#202020;}
    .pd_bg{float:left;height:100%;width:100%;background:#000;opacity:0.5;}
</style>

<div id="pd_scar">
    <div class="pd_scar2">
        <p class="pd_scar_a">商品已经成功加入购物车！</p>
        <p class="pd_scar_b">
            <a href="<?=Url::toRoute(['/shop/item/list','id'=>$id])?>">继续逛逛</a>
            <a href="<?=Url::toRoute(['/shop/cart/list','id'=>$id])?>">去购物车结算</a>
        </p>
    </div>
    <div class="pd_bg pd-hide"></div>
</div>

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
            <span class="de-label">类目</span>
            <span class="de-value"><?=$model['category']?></span>
        </li>
        <?php if($model['else_attr']):?>
            <?php foreach($model['else_attr'] as $row):?>
                <li class="de-row">
                    <span class="de-label"><?=$row['name']?></span>
                    <span class="de-value"><?=$row['value']?></span>
                </li>
            <?php endforeach;?>

        <?php endif;?>
    </ul>
    <hr class="de-line-row">
    <div class="de-row" style="padding-top: 5%;position: relative;width: 100%;">
        <?=$model['describe']?>
    </div>
    <div class="h-fixed-bottom">
        <a id="add_cart">
            加入购物车
        </a>
    </div>

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