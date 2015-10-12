<?php
use app\components\PassthroughWidget;
use yii\helpers\Url;
$this->title = '配件列表';
Yii::$app->params['layoutBottomHeight'] = 40;

?>
    <style>
        <?php $this->beginBlock('CSS') ?>
        .item-list{
            display: block;
            padding: 12px 0;
            width: 90%;
            margin: 0 5%;
            border-bottom: 1px #e3e3e3 solid;
            font-size: 16px;
        }
        .item-list img{
            width: 80px;
            height: 60px;
            float: left;
        }
        .item-list .img-status{
            width: 80px;
            height: 80px;
            float: left;
            margin-right: 10px;
            color: #666;
            text-align: center;
        }
        .item-list span h5{
            line-height: 20px;
            min-height: 60px;
            color: #666;
            font-weight: normal;
            font-size: 16px;
        }
        .item-list span h6{
            height: 30px;
            color: #666;
            font-weight: normal;
            font-size: 16px;
        }
        .mtm_p{
            text-align: right;
            color: #999;
            font-size: 14px;
        }
        .mtm_p b{
            float: left;
            font-size: 16px;
            font-weight: 500;
            color: #b10000;
        }
        .scan-btn{ display:none;}

        .fault-info{
            color: #b18b8d;
        }
        .item-more{
            width: 80%; background-color: #efefef;font-size: 14px;
            /*box-shadow: 1px 1px 2px #cccccc; */
            border: 1px solid #EEEEEE;
            text-align: center; height: 36px; line-height: 36px;
            margin: 15px auto; border-radius: 4px; color: #666666;
        }
        .item-more-end{
            background-color: #FFFFFF;color: #cccccc;
            border: 0px;
        }
        <?php $this->endBlock() ?>
    </style>
<?php
$this->registerCss($this->blocks['CSS']);
?>

<div id="item-list-wrap">
    <?php if($model):?>
        <?php foreach($model as $row):?>
            <div class="item-list">
                <div class="img-status">
                    <img src="<?=$row['cover']?>">
                    <?=\app\modules\shop\models\Shop::getParts($row['status'])?>
                </div>
               <span>
                   <h5>(<?=$row['category']?>)<?=$row['name']?>
<!--                       <span class="fault-info">维修：全部消息文字消息保存</span>-->
                   </h5>
                   <p class="mtm_p">
                       <b class="scan-btn" data-url="<?=Url::toRoute(['/shop/parts/bing','part_id'=>$row['parts_id'],'item_id'=>$row['item_id'],'id'=>$row['wx_id'],'fault_id'=>$fault_id])?>">扫描配件绑定</b>
                       <a class="parts-cancel-btn" parts-id="<?=$row['parts_id']?>" href="javascript:void(0)">取消</a></p>
               </span>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>

<a class="h-fixed-bottom" href="<?=Url::toRoute(['/shop/parts/list','id'=>$id,'fault_id'=>$fault_id])?>">
            申请配件
        </a>


<script>
    <?php $this->beginBlock('JS_END') ?>
    var fault_id = fault_id || <?=$fault_id?>;
    var hasClick = 0;

    function getUrlParam(name,url) {
        var reg = new RegExp("[\?|&]" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
        var r = url.match(reg);  //匹配目标参数
        if (r != null) return unescape(r[1]);
        return null; //返回参数值
    }

    $(function(){
        $('.parts-cancel-btn').click(function(){
            if(hasClick == 1) return false;
            var $this = $(this).closest('.item-list');
            var parts_id = $(this).attr('parts-id');
            $.post(
                '<?=Url::toRoute(['cancel','id'=>$id])?>&parts_id='+parts_id,
                function(resp){
                    if(resp.status == 1)
                        $this.remove();
                    else alert( resp.msg );
                    hasClick = 0;
                },'json'
            );
        });

        $('.scan-btn').click(function(){
            var url = $(this).attr('data-url');
            wx.scanQRCode({
                needResult: 1,  // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                    var str = '&un='+getUrlParam('un',result);
                    if( getUrlParam('item_id',url) != null){
                        if(getUrlParam('item_id',url) != getUrlParam('item',result)){
                            alert('二维码不合法');
                            return false;
                        }
                    }else
                        str = '&item_id=' + getUrlParam('item_id',result);


                    if( getUrlParam('id',url) != null){
                        if( getUrlParam('id',url) != getUrlParam('id',result)) {
                            alert('二维码不合法');
                            return false;
                        }
                    }else
                        str = '&id=' + getUrlParam('id',result);

                    location.href = url + str;
                }
            });
        });
    });
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

\app\components\WxjsapiWidget::widget([
    'wx_id'=>$id,
    'apiList'=>['scanQRCode'],
    'jsReady'=>'
        var ele = document.querySelectorAll(".scan-btn");
        for(var i in ele)
            ele[i].style.cssText="display:block";'
]);
?>