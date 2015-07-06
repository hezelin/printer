<?php
use yii\helpers\Url;
use \app\modules\shop\models\Shop;
$this->title = '订单详情';
?>

<style>

    #order-detail{
        margin: 0 3%;
        font-size: 16px;
    }
    .order-title {
        border-bottom: 1px #202020 solid;
        margin-top: 1em;
        line-height: 2em;
        font-size: 14px;
    }

    .pay-type-box dd{
        display:block;
        width:32%;
        margin:3% 2% 2% 0;
        border:1px #f3f3f3 solid;
        float:left;
        text-align:center;
        height: 60px;
        line-height: 20px;
        padding: 10px;
        position:relative;
        font-size: 14px;
    }

    #order-list li{
        font-size: 14px; color: #444;
        border-bottom: 1px solid #cccccc;
        padding:15px 0;
    }
    .order-cover{
        width: 80px;
        height: 60px;
        float: left;
        margin-right: 15px;
    }
    .order-remark{
        padding-top:15px;
    }

    .order-name{height: 30px; line-height: 30px; overflow: hidden; font-weight: 400;}
    .order-num-price{ height: 30px; line-height: 30px; }
    .order-num-price b{ color: #b10000; float: right; margin-right: 3%;}

    .order-info{
        background: #EEEEEE;
        font-size: 14px;
        color: #444;
        margin-top: 15px;
        padding: 5px 10px;
    }
    .order-info dd{ height: 24px; line-height: 24px}
    .order-info dd.last-dd{ margin-bottom: 5px;}
    .order-info b{ float: right; font-weight: 400;}

    .order-info dt{ font-size: 16px; border-top: 1px dashed #999; height: 36px; line-height: 36px;}
    .order-info dt b{color: #b10000; font-weight: 600}

    .address-text p{
        height: 20px;
        line-height: 20px;
        font-size: 14px;
    }

    .address-btn a{
        width: 50%;
        display: block;
        float: left;
        text-align: center;
    }

    .order-status,.order-address{
        font-size: 14px; color: #444;
        padding-top:15px;
    }
    .order-status p{
        color: #999999;
    }
    .order-status span{
        color: #444444;
    }

    .check-word span{
        color: #ff0000;
    }

    .order-btn-group{ height: 36px; margin-top: 15px; border: 1px solid #EEEEEE;
    }
    .order-btn{
        width: 50%;
        height: 36px; line-height: 36px;
        text-align: center;
        float: left;
        color: #333333;
    }
    .order-btn-left{ border-right: 1px solid #EEEEEE;}
    .btn-failure{ color: #cccccc;}
</style>

<div id="order-detail">
    <div class="order-title">订单状态</div>
    <div class="order-status">
        <p>订单编号：<span><?=$model['order_id']?></span></p>
        <p>下单时间：<span><?=date('Y-m-d H:i',$model['add_time'])?></span></p>
        <p>付款方式：<span><?=Shop::getPayStatus($model['pay_status'])?></span></p>
        <?php if($model['check_word']):?>
        <p class="check-word">审核失败：<span><?=$model['check_word']?></span></p>
        <?php endif;?>
        <div class="order-btn-group">
            <a class="order-btn order-btn-left"><?=Shop::getOrderStatus($model['order_status'])?></a>
            <a class="order-btn btn-failure">查看物流</a>
        </div>
    </div>
    <div class="order-title">收件人信息</div>
    <div class="order-address">
        <p><?=$model['name'],' , ',$model['phone']?></p>
        <p><?=$model['city'],$model['address']?></p>
    </div>
    <?php if($model['remark']):?>
    <div class="order-title">给卖家留言</div>
    <p class="order-remark"><?=$model['remark']?></p>
    <?php endif;?>

    <div class="order-title">商品清单</div>
    <ul id='order-list'>
        <?php foreach($item as $i):?>
            <li>
                <img class="order-cover" src="<?php echo $i['cover'];?>">
                <h3 class="order-name"><?php echo $i['name'];?></h3>
                <p class="order-num-price">
                    共 <?php echo $i['item_nums'];?> 件 <b>￥<?=number_format($i['item_nums']*$i['price'],2,'.','')?></b>
                </p>
            </li>
        <?php endforeach;?>
    </ul>

    <dl class="order-info">
        <dd>运费<b id="order-express-money">￥<?=$model['freight']?></b></dd>
        <dd class="last-dd">使用积分<b><?=$model['pay_score']?></b></dd>
        <dt>支付金额<b>￥<span id="order-total-money"><?=number_format($model['total_price'],2,'.','')?></span></b></dt>
    </dl>

</div>
<p>&nbsp;</p>

<script>
    <?php $this->beginBlock('JS_END') ?>
    $(function(){

    });
    <?php $this->endBlock();?>
</script>


<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
