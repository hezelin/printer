<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Alert;
$this->title = '修改资料';
?>

<div id="list-wrap">

    <div class="list-head">用户信息 <em class="close-em">展开</em></div>
    <ul class="list-group">
        <li class="list-group-item"><span class="list-label">用户：</span><span class="list-text"><?=$rent['name']?></span></li>
        <li class="list-group-item"><span class="list-label">地址：</span><span class="list-text"><?=$rent['address']?></span></li>
        <li class="list-group-item">
            <span class="list-label">租金：</span><span class="list-text">最低消费<?=$rent['monthly_rent']?>元，付款周期：<?=$rent['rent_period']?>个月，黑白 <?=$rent['black_white']?>元/张
                <?php
                if($rent['colours'])
                    echo '，彩色：',$rent['colours'],'元/张';
                ?>
        </span>
        </li>
        <li class="list-group-item">
            <span class="list-label"><?=$lastCharge? '上次读数':'初始读数'?>：</span><span class="list-text"><?php
                if($lastCharge){
                    echo '黑白：',$lastCharge['black_white'],'张';
                    if($rent['colours'])
                        echo '，彩色：',$lastCharge['colour'],'张';
                    echo '，租金：',$lastCharge['total_money'],'元，超出金额：',$lastCharge['exceed_money'],'元，收取时间：',date('Y-m-d H:i',$lastCharge['add_time']);
                }else
                {
                    echo '黑白：',$rent['black_amount'],'张';
                    if($rent['colours'])
                        echo '，彩色：',$lastCharge['colour_amount'],'张';
                }
                ?>
        </span>
        </li>
    </ul>
</div>
<p style="height: 50px; line-height: 50px; display: block">&nbsp;</p>
<style>
    #list-wrap{ position: fixed; top: 0; left: 0; width: 100%; display: block; background-color: #FFFFFF;}
    .list-group,.list-group-item{ list-style: none; padding: 0; margin: 0;}
    .list-group{ font-size: 16px; padding:10px 0; display: none;
        float: left;
        border-bottom: 1px solid #cccccc;  box-shadow: 0 2px 10px #eee;}
    .list-head{ height: 50px; line-height: 50px; color: #4684CF; padding: 0 15px; border-bottom: 1px solid #cccccc; font-weight: 600; font-size: 16px; }
    .list-head .close-em{ font-style: normal; position: absolute; right: 15px; top: 0;}

    .list-group-item{ padding: 5px 15px 0; width: 100%; float: left; font-size: 14px;}
    .list-label{ width: 30%; float: left; color: #000;}
    .list-text{ width: 70%; float: left; color: #666;}

    #form-wrap{padding: 10px 15px; width: 100%; font-size: 16px;}
    .row{ height: 40px; border-bottom: 1px solid #cccccc;}
    #form-wrap input{ height: 36px;line-height: 30px; border: none; padding: 0; margin: 0; outline: none; font-size: 14px;
        color: #999;}
    button{ outline: none;}
    #form-wrap .list-label{ line-height: 36px;}
    .row-end{ border-bottom: none; padding-top: 10px;}

    .h-img-box3{
        width: 60px;
        height: 60px;
        line-height: 60px;
        vertical-align: middle;
        text-align: center;
        overflow: hidden;
        float: left;
        margin: 1px 15px 15px 1px;
        background-color: #fff;
        border: 1px dotted #cccccc;
    }
    .h-img-box3 img{ max-width: 60px; max-height: 60px;}
</style>

<div id="form-wrap">
    <form method="post" id="wechat-form">
        <div class="row">
            <span class="list-label">黑白</span>
            <span class="list-text"><input class="rent-num check-btn" data-type="black_white" data-name="last_black" data-tips="黑白" type="text" name="TblRentReport[black_white]" placeholder="张数" value="<?=$model->black_white?>" /> </span>
        </div>
        <?php if($rent['colours']):?>
            <div class="row">
                <span class="list-label">彩色</span>
                <span class="list-text"><input class="rent-num check-btn" data-type="colours" data-name="last_colour" data-tips="彩色" type="text" name="TblRentReport[colour]" placeholder="张数" value="<?=$model->colour?>" /> </span>
            </div>
        <?php endif;?>
        <div class="row">
            <span class="list-label">租金</span>
            <span class="list-text"><input class="rent-money check-btn" data-tips="租金" type="text" name="TblRentReport[total_money]" placeholder="最多2位小数" value="<?=$model->total_money?>" /> </span>
        </div>

        <input type="hidden" id="tblrentreport-exceed_money" class="exceed-money" name="TblRentReport[exceed_money]" value="<?=$model->exceed_money?>">
        <input type="hidden" name="TblRentReport[sign_img]" id="sign-img" value="<?=$model->sign_img?>">

        <div class="row">
            <span class="list-label">下次收租</span>
            <span class="list-text"><input type="text" value="<?=$model->next_rent?>" name="TblRentReport[next_rent]" placeholder="格式：2016-08-08" /> </span>
        </div>

        <div class="row row-end">
            <span class="list-label">签名</span>
            <span class="list-text"><div id="upload-img" class="h-img-box3"> <img src="<?=$model->sign_img?>" /> </div></span>
        </div>

        <button id="wechat-submit" class="h-link">保存</button>
    </form>
</div>

<script>
    var Rent_price = <?= json_encode([
        'monthly_rent'=>(int)$rent['monthly_rent'],
        'colours'=>(float)$rent['colours'],
        'black_white'=>(float)$rent['black_white'],
        'rent_period'=>(int)$rent['rent_period'],
        'last_black'=> isset($lastCharge['black_white'])? (int)$lastCharge['black_white']:$rent['black_amount'],
        'last_colour'=> isset($lastCharge['colour'])? (int)$lastCharge['colour']:$rent['colours_amount']
    ])?>;

    <?php $this->beginBlock('JS_END') ?>
    var localId;
    $(function(){
        $('.rent-num').change(function(){
            var money = 0;
            $('.rent-num').each(function(){
                if( $.trim($(this).val()) )
                    money += (parseInt($(this).val())-Rent_price[$(this).attr('data-name')]) * Rent_price[$(this).attr('data-type')] ;
            });
            if( Rent_price.monthly_rent * Rent_price.rent_period >= money){
                money = Rent_price.monthly_rent * Rent_price.rent_period;
                $('.exceed-money').val(0);
            }
            else{
                $('.exceed-money').val ( (money - Rent_price.monthly_rent * Rent_price.rent_period).toFixed(2) )
            }

            $('.rent-money').val( money.toFixed(2));
        });

        $('.close-em').click(function(){
            if( $(this).hasClass('em-active') ){
                $('.list-group').hide();
                $(this).removeClass('em-active').text('展开');
            }else{
                $('.list-group').show();
                $(this).addClass('em-active').text('关闭');
            }
        });

        $("#wechat-submit").click(function () {
            var step = 0;
            $.each( $('.check-btn'),function(){
                if( !$.trim($(this).val()) ){
                    alert( $(this).attr('data-tips') + ' 不能为空！');
                    step = 1;
                    return false;
                }
            });
            if( step == 1) return false;
            if( localId ==  undefined ){
                alert("请选择签名图片");
                return false;
            }

            wx.uploadImage({
                localId: localId,
                success: function (res) {
                    document.getElementById("sign-img").value = res.serverId;
                    document.getElementById("wechat-form").submit();
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });

            return false;
        });
    });
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>

<?php
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$wx_id,
    'apiList'=>['chooseImage','uploadImage'],
    'jsReady'=>'
    document.querySelector("#upload-img").onclick = function () {
        wx.chooseImage({
            count: 1,
            success: function (res) {
                localId = res.localIds[0];
                var imgs = "<img src=\" "+ localId + "\"/>";
                document.getElementById("upload-img").innerHTML = imgs;
            }
        });
    };'
])
?>