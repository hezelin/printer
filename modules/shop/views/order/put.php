<?php
use yii\helpers\Url;
$this->title = '订单结算';
?>

<style>

    #order-detail{
        margin: 0 3%;
        font-size: 16px;
    }

    .btn-add{
        width: 97%;
        margin: 15px auto; height:36px; line-height:36px; font-size: 16px;
        text-align: center;  display: block; border-radius: 4px;
        border: 1px solid #cccccc;
    }
    .order-title {
        border-bottom: 1px #202020 solid;
        margin-top: 1em;
        line-height: 2em;
        font-size: 14px;
    }

    .pay-type-box{
        padding: 10px 0;
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
    .pay-type-box .pay-active{color:#4684cf;border-color:#4684cf;}
    .pay-icon{
        background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoyMDNCREU1QjFDMjJFNTExOEU2NkFEQTE2QUVBRDIyQiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpBMEQxMjI1MDIyMUMxMUU1OUIxQkE2ODlFMDM3NDc3RiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpBMEQxMjI0RjIyMUMxMUU1OUIxQkE2ODlFMDM3NDc3RiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyMDNCREU1QjFDMjJFNTExOEU2NkFEQTE2QUVBRDIyQiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyMDNCREU1QjFDMjJFNTExOEU2NkFEQTE2QUVBRDIyQiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgTGVh8AAAEQSURBVHjaxNc9CsIwGIDhJCo66CA6ioN0cPQUegQHHdw8hKM6CN5AL+Ip9CYubi72KxQqTdvk+0kCGYqQ522Mlerl6akijp2JiafzbmLi6WyZmDhcmJh46IASHjLAiocKqMRDBNTi0gGNuGSAEy4V4IxLBHjh3AHeOGcACucKQOMcASScGkDGKQEsODbA/6emNVuANz4ZddVtP1ePw8L6eVsav2wSNR501Or8Iu2ACO4aIIa7BPzh03EvW5wLbwoo3TkcJli8KsIXrwuwbjssCovbIjB4VUDtd26LwOK2AKcDl0dct0l2Lo7rGQqHoQsvp96nHR4u789XDfs4vLgDqGc7oBQ8DyD9sVBwGD8BBgCdmlQNBNXVjAAAAABJRU5ErkJggg==) no-repeat center center;
        width: 32px;
        height: 32px;
        position: absolute;
        bottom: 0;
        right: 0;
        display: none;
    }
    .pay-address{
        color:red;margin-top:0.5em; display: none;
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
    .order-remark{
        padding-top:15px;
    }
    .order-remark-input{
        height: 48px; line-height: 24px;
        border: 1px solid #cccccc;
        padding: 0 5px;
        width: 100%;
        color: #444444;
        font-size: 16px;
        border-radius: 0;
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

    .score-area{
        margin-top: 10px;
        height: 40;
        padding: 10px 10px 10px 0;
        background: #EEEEEE;
        text-align: right;
        font-size: 12px;

    }
    #use-score{
        width: 60px;
        padding: 0 5px;
        height: 24px;
        line-height: 24px;
    }
    #use-score-money{
        color: #b10000;
        font-size: 16px;
    }
    .score-all{
        color: #999999;
        height: 24px;
        line-height: 30px;
    }
    .score-hidden{ display: none;}

    .address-text{
        margin-top: 10px;
        padding: 5px 10px;
        border: 1px solid #cccccc;
        border-radius: 4px 4px 0 0;
    }
    .address-text p{
        height: 20px;
        line-height: 20px;
        font-size: 14px;
    }
    .address-btn{
        height: 36px;
        line-height: 36px;
        background-color: #FFFFFF;
        position: relative;
        border-radius: 0 0 4px 4px ;
        border: 1px solid #cccccc;
        border-top: none;

    }
    .address-btn a{
        width: 50%;
        display: block;
        float: left;
        text-align: center;
    }
    .border-left{ border-right: 1px solid #cccccc}

</style>

<form method="post">

<div id="order-detail">
    <div class="order-title">收货人信息</div>
    <?php if(isset($address['id'])):?>
        <div class="address-text">
            <p><?=$address['name'],',',$address['phone']?></p>
            <p><?=$address['city'],$address['address']?></p>
        </div>
        <div class="address-btn">
            <a class="border-left" href="<?=Url::toRoute(['/shop/address/update','id'=>$id,'address_id'=>$address['id'],'url'=>Yii::$app->request->url])?>">修改地址</a>
            <a href="<?=Url::toRoute(['/shop/address/list','id'=>$id,'url'=>Yii::$app->request->url])?>">切换地址</a>
        </div>
    <?php else:?>
        <a class="btn-add" href="<?=Url::toRoute(['/shop/address/add','id'=>$id,'url'=>Yii::$app->request->url])?>" >+添加新地址</a>
    <?php endif;?>

    <div class="order-title">支付方式</div>
    <dl class="pay-type-box">
        <dd class="pay-type pay-active" data-type="1" data-freight="0">货到付款<br>免运费<i class="pay-icon"></i></dd>
        <dd class="pay-type" data-type="3" data-freight="0">在线支付<br>免运费<i class="pay-icon"></i></dd>
        <dd class="pay-type" data-type="2" data-freight="0" style="margin-right:0;">上门自取<br>免运费<br><i class="pay-icon"></i></dd>
    </dl>
<!--    <p class="pay-address">上门自取地址为：广州市广园东路1881号B栋4楼B03   电话：4000-228-168</p>-->

    <div class="order-title">给卖家留言</div>
    <div class="order-remark">
        <textarea name="TblShopOrder[remark]" class="order-remark-input" id="order-remark" placeholder="选填"></textarea>
    </div>
    <div class="order-title">商品清单</div>
    <ul id='order-list'>
        <?php foreach($model as $i):?>
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
        <dd>商品金额<b>￥<?=number_format($total['price'],2,'.','')?></b></dd>
        <dd>运费<b id="order-express-money">0</b></dd>
        <dd>赠送积分<b><?=$total['giveScore']?></b></dd>
        <dd class="last-dd">可使用积分<b><?=$total['useScore']?></b></dd>
        <dt>订单总额<b>￥<span id="order-total-money"><?=number_format($total['price'],2,'.','')?></span></b></dt>
    </dl>
<?php if($total['useScore']>0 && $total['score']>0 ):?>
    <div class="score-area">
        <input type="checkbox" id="score-select" /> &nbsp;使用积分
        <span class="score-hidden score-inupt">
            <input name="use_score" id="use-score" /> 个 &nbsp;&nbsp;
            <span id="use-score-money">￥-<b>0.00</b></span>
        </span>
        <div class="score-hidden score-all">您有积分 <?=$total['score']?> 个</div>
    </div>
<?php endif;?>
</div>
<p>&nbsp;</p>
<button id="order-submit-btn" href="<?=Url::toRoute(['/shop/order/put'])?>" class="h-button" style="width: 94%">提交订单</button>


    <input type="hidden" name="TblShopOrder[address_id]" id="form-address" value="<?=$address['id']?>">
    <input type="hidden" name="TblShopOrder[order_status]"  id="form-status" value="1">
    <input type="hidden" name="TblShopOrder[total_price]"  id="form-money">
    <input type="hidden" name="TblShopOrder[pay_score]"  id="form-score" value="0">
    <input type="hidden" name="TblShopOrder[openid]" value="<?=$openid?>">
</form>

<script>
    <?php $this->beginBlock('JS_END') ?>
    var total_score = <?=$total['score']?>;
    var total_money = <?=$total['price']?>;
    var express_money = 0;
    var use_score = <?=$total['useScore']?>;
    var use_score_money = 0;

    $(function(){
        // 选择支付状态
        $('#order-detail .pay-type').click(function(){
            $(this).addClass('pay-active').siblings().removeClass('pay-active');
            var status = $(this).attr('data-type');
            $('#form-status').val(status);
            express_money = parseInt( $(this).attr('data-freight') );
            $('#order-express-money').text( express_money);
            $('#order-total-money').text( (total_money + express_money - use_score_money).toFixed(2) )
            if(status == 2)
                $('.pay-address').show();
            else
                $('.pay-address').hide();
        });
        // 积分支付
        $('#score-select').click(function(){
            if($(this).attr("checked")){
                var this_score = parseInt( $('#use-score').val() ) || 0;
                use_score_money = (this_score/100).toFixed(2);
                $('.score-hidden').show();
            }else{
                $('.score-hidden').hide();
                use_score_money = 0;
            }
            $('#order-total-money').text( (total_money + express_money - use_score_money).toFixed(2) )
        });
        $('#use-score').change(function(){
            var this_score = parseInt( $(this).val() ) || 0;
            if( this_score > total_score ){
                this_score = total_score;
                $(this).val(this_score);
            }
            if( this_score > use_score){
                this_score = use_score;
                $(this).val(this_score);
            }
            use_score_money = (this_score/100).toFixed(2);
            $('#use-score-money b').text( use_score_money );
            $('#order-total-money').text( (total_money + express_money - use_score_money).toFixed(2) )

        });
        // 提交订单
        $('#order-submit-btn').click(function(){
            if( !$('#form-address').val() ){
                alert('请添加地址!');
                return false;
            }
            $('#form-money').val( total_money + express_money - use_score_money );
            $('#form-score').val( use_score_money*100);
        })
    });
    <?php $this->endBlock();?>
</script>


<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>
