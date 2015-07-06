<?php
$this->title = '商品二维码';
?>
<style>
    #cart-list li{
        border-bottom: 1px solid #ccc;
        height: 120px;
        width: 90%;
        margin: 0 5%;
        color: #444;
    }
    #cart-list .item-li{
        display: block;
        height: 60px;
        line-height: 30px;
        font-size: 16px;
        margin-top: 10px;
        overflow: hidden;
        vert-align: middle;

    }
    #cart-list .item-li img{
        height: 60px;
        width: 60px;
        float: left;
        margin-right: 15px;
    }
    #cart-list .sns{
        height: 36px;
        line-height: 32px;
        text-align: right;
        margin-top: 15px;
        color: #b10000;
        font-size: 16px;
    }
    
    #cart-list .cart-left{
        float: left;
        color: #444444;
        font-size: 14px;
        height: 30px;
    }

    #cart-list .item-del{
        cursor: pointer;
        margin-left: 20px;
        font-size: 16px;
    }

    .btn-add{
        display: block;
        height: 36px;
        width: 36px;
        line-height: 28px;
        text-align: center;
        border: 1px #d7d7d7 solid;
        float: left;
        font-size: 1em;
        cursor: pointer;
    }

    .item-count{
        width: 3em;
        float: left;
        text-align: center;
        font-size: 1em;
        border-top: 1px solid #d7d7d7;
        border-bottom: 1px solid #d7d7d7;
        border-left: none;
        border-right: none;
        height: 36px;
        border-radius: 0;
    }
    .cart-total{
        text-align: right;
        height: 50px;
        line-height: 50px;
        font-size: 18px;
        margin-right: 5%;
    }
    .cart-money{
        color: #b10000;
        padding-left: 15px;
    }

</style>

<div id="cart-list">
        <ul>
    <?php foreach($model as $o):?>
        <li data-id="<?=$o['id']?>" data-price="<?=$o['price']?>" data-nums="<?=$o['item_nums']?>">
            <a class="item-li" href="<?=\yii\helpers\Url::toRoute(['/shop/item/detail','id'=>$id,'item_id'=>$o['id']])?>">
                <img src="<?=$o['cover']?>" />
                <span><?=$o['name']?></span>
            </a>
            <div class="sns">
                <span class="cart-left">
                    <b class="btn-add" data-opera="minus">-</b>
                    <input type="text" class="item-count" value="<?php echo $o['item_nums'];?>">
                    <b class="btn-add" data-opera="add">+</b>
                    <span class="item-del" data-opera="del">删除 </span>
                </span>
                <b>￥<span class="item-price"> <?=$o['price']*$o['item_nums']?> </span></b>
            </div>
        </li>
    <?php endforeach;?>
         </ul>
    <br/>
    <div class="cart-total">
            共 <span id="cart-count"><?php echo $total;?></span> 件
            <b class="cart-money">￥<span id="cart-money"><?php echo $totalPrice;?></span></b>
    </div>
    <a href="<?=\yii\helpers\Url::toRoute(['/shop/order/put','id'=>$id])?>" class="h-button">立即结算</a>
    <br/>
    <a href="<?=\yii\helpers\Url::toRoute(['/shop/item/list','id'=>$id])?>" type="button" class="h-button-default">继续添加</a>
</div>


<script>
    <?php $this->beginBlock('JS_END') ?>
    function cartOpera(wrap,num)
    {
        var id = wrap.attr('data-id');
        var price = parseFloat( wrap.attr('data-price') );
        var oNum = parseInt( wrap.attr('data-nums') );

        $.post(
            '/shop/cart/opera',
            {'id':id,'num':num},
            function(res){
                if(res.status == 1){
                    if(num == 0){
                        var totalNum = parseInt( $('#cart_count').text()) - oNum;
                        $('#cart_count').text( totalNum );
                        $('#cart_money').text( parseFloat( $('#cart_money').text())- price*oNum );
                        wrap.remove();
                        var diffPrice = (num - oNum)*price
                        $('#cart-count').text( parseInt( $('#cart-count').text()) + (num - oNum) );
                        $('#cart-money').text( parseFloat( $('#cart-money').text()) + diffPrice );
                        if( parseInt( $('#cart-count').text()) == 0){
                            $('#cart-list').html(
                                '<p class="h-hint">购物车是空的！</p>' +
                                '<a class="h-button" href="<?=\yii\helpers\Url::toRoute(['/shop/item/list','id'=>$id])?>">去商城购物</a>'
                            )
                        }
                    }else{
                        var diffPrice = (num - oNum)*price
                        wrap.find('.item-price').text( parseFloat(wrap.find('.item-price').text()) + diffPrice );
                        wrap.find('.item-count').val(num);
                        $('#cart-count').text( parseInt( $('#cart-count').text()) + (num - oNum) );
                        $('#cart-money').text( parseFloat( $('#cart-money').text()) + diffPrice );
                        wrap.attr('data-nums',num);
                    }
                    hasClick = 0;
                }else
                    alert(res.error);
            },
            'json'
        );
    }
    var hasClick = 0;
    $(function(){

        $('.btn-add,.item-del').click(function(){
            if( hasClick == 1 ) return false;
            var opera = $(this).attr('data-opera');
            var wrap = $(this).closest('li');
            var $input = $(this).parent().children('input');
            if(opera == 'del')
                cartOpera(wrap,0);
            else if(opera == 'add')
                cartOpera(wrap, parseInt($input.val()) + 1);
            else if(opera == 'minus')
                cartOpera(wrap, parseInt($input.val()) - 1);
        });
        $('#cart-list .item-count').change(function(){
            var wrap = $(this).closest('li');
            cartOpera(wrap, $(this).val());
        });
    })
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>