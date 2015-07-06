<?php
use yii\helpers\Url;
use app\modules\shop\models\Shop;
    $this->title = '我的订单';
?>
<style>
    #order-view{
        margin: 0 3%;
    }
    .order-title {
        border-bottom: 1px #202020 solid;
        margin-top: 1em;
        line-height: 2em;
        font-size: 14px;
    }

    .pay-active .pay-icon{ display: block;}

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
    .order-name{height: 30px; line-height: 30px; overflow: hidden; font-weight: 400;}
    .order-name b{float: right; margin-right: 3%;}
    .order-num-price{ height: 30px; line-height: 30px; }
    .order-num-price b{ color: #b10000; float: right; margin-right: 3%;}

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

<div id="order-view">
    <div class="order-title">订单列表</div>
    <ul id='order-list'>
        <?php foreach($model as $m):?>
            <li>
                <a href="<?=Url::toRoute(['/shop/order/detail','id'=>$id,'order_id'=>$m['order_id']])?>">
                    <img class="order-cover" src="<?=$m['cover']?>">
                    <h3 class="order-name"><?=date('Y-m-d H:i',$m['add_time'])?><b> > </b></h3>
                    <p class="order-num-price">
                        共 <?=$m['total_num']?> 件 <b>￥<?=number_format($m['total_price'],2,'.','')?></b>
                    </p>
                </a>
                <div class="order-btn-group">
                    <a class="order-btn order-btn-left"><?=Shop::getOrderStatus($m['order_status'])?></a>
                    <a class="order-btn btn-failure">查看物流</a>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
</div>