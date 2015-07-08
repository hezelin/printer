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
                    <?= Shop::getOrderBtn($m['order_status'],$m['order_id'],$id) ?>
                </div>
            </li>
        <?php endforeach;?>
    </ul>
</div>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        $(function(){
            // ajax 操作，取消、删除订单
            var hasClick =0;
            $('.order-btn-ajax').click(function(){
                if(hasClick == 1) return false;
                hasClick = 1;
                var type = $(this).attr('data-type');
                var orderId = $(this).attr('data-order');
                var $this = $(this);
                $.post(
                    '<?=Url::toRoute(['/shop/order/status','id'=>$id])?>&order_id='+orderId,
                    {status:type},
                    function(resp){
                        if(resp.status == 1)
                        {
                            if(type == 'delete'){
                                $this.text('已删除').addClass('btn-failure').closest('li').remove();
                            }else{
                                $this.attr('data-type','delete').text('删除订单');
                                $this.prev('a').text('已取消').addClass('btn-failure');
                            }
                        }else alert(resp.msg);
                        hasClick = 0;
                    },'json'
                );
            });
            // 取消订单
            $('.order-btn-cancel').click(function(){
                return false;
            });
        });
        <?php $this->endBlock();?>
    </script>
<?php
    \app\assets\ZeptoAsset::register($this);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>