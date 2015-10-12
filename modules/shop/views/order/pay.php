<?php
use yii\helpers\Url;
use \app\modules\shop\models\Shop;
$this->title = '订单详情';
?>

<style>

    .order-success{
        margin: 5%;
        font-size: 14px;
        padding: 15px;
        border: 1px solid #cccccc;
        background-color: #f8f8f8;
    }

    .order-success h2{
        font-size: 20px;
        height: 30px;
    }
    .order-success p{
        height: 24px; line-height: 24px;
    }
    .order-success b{
        font-weight: 500;
    }
    .order-success .price{
        color: #b10000;
        font-size: 16px;
        font-weight: 600;
    }
    .order-btn{
        width: 90%;
        margin-top: 25px;
        margin-bottom: 0;
    }
</style>
<div class="order-success">
    <h2>订单提交成功</h2>
    <p>订单号：<b><?=$order_id?></b></p>
    <p>应付金额：<b class="price">￥<?=number_format($price,2,'.','')?>元</b></p>
    <p>支付方式：<b><?=Shop::getPayStatus($payStatus)?></b></p>
</div>
<?php if($payStatus==3):?>
<a href="#" class="h-button order-btn">微信支付</a>
<a href="<?=Url::toRoute(['/shop/order/detail','id'=>$id,'order_id'=>$order_id])?>" type="button" class="h-button-default order-btn">订单详情</a>
<?php else:?>
    <a href="<?=Url::toRoute(['/shop/order/detail','id'=>$id,'order_id'=>$order_id])?>" type="button" class="h-button order-btn">订单详情</a>
<?php endif;?>
<a href="<?=Url::toRoute(['/shop/i/order','id'=>$id])?>" type="button" class="h-button-default order-btn">返回我的订单</a>
<a href="<?=Url::toRoute(['/wechat/index','id'=>$id])?>" type="button" class="h-button-default order-btn">返回首页</a>
