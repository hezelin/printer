<?php
$this->title = '我的购物车';
$this->registerMetaTag(['http-equiv'=>'Cache-Control','content'=>'no-cache, no-store, must-revalidate']);
$this->registerMetaTag(['http-equiv'=>'Pragma','content'=>'no-cache']);
$this->registerMetaTag(['http-equiv'=>'Expires','content'=>'0']);
use app\assets\AuicssAsset;

AuicssAsset::register($this);
?>
<style>
    /*#cart-list li{  border-bottom: 1px solid #ccc;  height: 120px;  width: 90%;  margin: 0 5%;  color: #444;  }
    #cart-list .item-li{  display: block;  height: 60px;  line-height: 30px;  font-size: 16px;  margin-top: 10px;  overflow: hidden;  vert-align: middle;  }
    #cart-list .item-li img{  height: 60px;  width: 60px;  float: left;  margin-right: 15px;  }
    #cart-list .sns{  height: 36px;  line-height: 32px;  text-align: right;  margin-top: 15px;  color: #b10000;  font-size: 16px;  }
    #cart-list .cart-left{  float: left;  color: #444444;  font-size: 14px;  height: 30px;  }
    #cart-list .item-del{  cursor: pointer;  margin-left: 20px;  font-size: 16px;  }
    .btn-add{  display: block;  height: 36px;  width: 36px;  line-height: 28px;  text-align: center;  border: 1px #d7d7d7 solid;  float: left;  font-size: 1em;  cursor: pointer;  }
    .item-count{  width: 3em;  float: left;  text-align: center;  font-size: 1em;  border-top: 1px solid #d7d7d7;  border-bottom: 1px solid #d7d7d7;  border-left: none;  border-right: none;  height: 36px;  border-radius: 0;  }
    .cart-total{  text-align: right;  height: 50px;  line-height: 50px;  font-size: 18px;  margin-right: 5%;  }
    .cart-money{  color: #b10000;  padding-left: 15px;  }*/
    .aui-counter-minus, .aui-counter-plus, input.aui-counter-input{
        height:32px;
    }
    body,.aui-list-view{
        background: #f8f8f8;
    }
    .aui-list-view .aui-img-object{
        height:46px;
        max-width:46px;
        line-height: 46px;
    }
    .aui-overflow{
        overflow:hidden;
    }
    .icon_dd{
        background:#e6e6e6;
        width:100px;
        overflow:hidden;
        font-size: 60px;
        height:100px;
        line-height:100px;
        margin:0 auto;
        border-radius:50%;
        margin-top:90px;
    }
</style>
<div id="cart-list" class="aui-content">
    <ul class="aui-list-view">
    <?php foreach($model as $o):?>
        <li data-id="<?=$o['id']?>" data-price="<?=$o['price']?>" data-nums="<?=$o['item_nums']?>" class="aui-list-view-cell aui-img aui-content" style="background: #fff; padding:11px 15px 10px 15px;">
            <a class="item-li aui-border-b aui-block" href="<?=\yii\helpers\Url::toRoute(['/shop/item/detail','id'=>$id,'item_id'=>$o['item_id']])?>">
                <div class="aui-arrow-right aui-ellipsis-1"></div>
                <img class="aui-img-object aui-pull-left" src="<?=$o['cover']?>" />
                <div class=aui-img-body">
                    <p class="aui-ellipsis-1" style="color:#333;font-size:16px;"><?=$o['name']?></p>
                    <div class="aui-text-danger" style="font-size:14px;">价格：￥<span class="item-price"> <?=$o['price']*$o['item_nums']?> </span></div>
                </div>
            </a>
            <div class="aui-clearfix"></div>
            <div class="aui-counter-box aui-danger" style="margin-top:22px; padding:0;">
                <div class="cart-left aui-counter aui-danger aui-pull-left">
                    <div class="btn-add aui-counter-minus aui-disabled" data-opera="minus"></div>
                    <input type="text" class="item-count aui-counter-input" value="<?php echo $o['item_nums'];?>">
                    <div class="btn-add aui-counter-plus" data-opera="add"></div>
                </div>
                <div class="aui-pull-left aui-padded-0-20" style="font-size: 14px; line-height:32px;"><?php echo $o['item_nums'];?>件</div>
                <div class="item-del aui-pull-right aui-bg-danger aui-padded-0-20" data-opera="del" style="color:#fff; border-radius:5px;">删除 </div>
            </div>
        </li>
    <?php endforeach;?>
    </ul>
    <div class="cart-total aui-content aui-border-t aui-overflow" style="padding:10px 0; background:#fff; height:54px; position: fixed; bottom:0;left:0; width:100%; margin-bottom: 0;">
        <div class="aui-col-xs-4 aui-text-center">
            <div >共 <span id="cart-count"><?php echo $total;?></span> 件</div>
            <div class="cart-money aui-text-danger">合计：￥<span id="cart-money"><?php echo $totalPrice;?></span></div>
        </div>
        <div class="aui-col-xs-4">
            <a href="<?=\yii\helpers\Url::toRoute(['/shop/item/list','id'=>$id])?>" type="button" class="aui-col-xs-11 aui-btn aui-pull-left">继续添加</a>
        </div>
        <div class="aui-col-xs-4">
            <button data-href="<?=\yii\helpers\Url::toRoute(['/shop/order/put','id'=>$id])?>" id="buy-btn" class="aui-col-xs-11 aui-btn-danger aui-pull-left">立即结算</button>
        </div>
    </div>
</div>


<script>
    <?php $this->beginBlock('JS_END') ?>
    function cartOpera(wrap,num)
    {
        var id = wrap.attr('data-id');
        var price = parseFloat( wrap.attr('data-price') );
        var oNum = parseInt( wrap.attr('data-nums') );

        $.ajax({
            url:'/shop/cart/opera',
            data:{id:id,num:num},
            type:'post',
            dataType:'json',
            sync:true,
            success:function(res){
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
                            $('#cart-list').html('\
                                    <div class="icon_dd aui-iconfont aui-icon-form aui-badge-danger aui-text-center"></div>\
                                    <div class="aui-text-center aui-padded-10" style="font-size:14px; margin-top:10px;">购物车是空的！</div>\
                                    <a class="aui-btn aui-btn-block aui-btn-outlined aui-bad-danger" style="width:100px; height:34px; line-height:30px; font-size:14px; margin:0 auto; padding:0; border-color:#999; color:#787878;" href="<?=\yii\helpers\Url::toRoute(['/shop/item/list','id'=>$id])?>">去商城购物</a>'
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
            }
        });
    }


    /*
     * 初始化购物车数据
     */
    function init(type)
    {
        if(type == 1){
            localStorage.cartHasLoad = 1;
            return true;
        }
        var u = navigator.userAgent;
        var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
        if( isiOS != true && localStorage.cartHasLoad == 1){
            localStorage.cartHasLoad = 0;
            location.reload()
        }
    }

    var hasClick = 0;
    $(function(){
        init(0);

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

        $('#buy-btn').click(function(){
            init(1);
            var url = $(this).attr('data-href');
            location.href = url;
        })
    });
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>