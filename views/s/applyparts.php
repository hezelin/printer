<?php
use yii\helpers\Url;
    $this->title = '申请配件';
?>
<div class="h-center-wrap">
    <a class="h-link" href="<?=Url::toRoute(['shop/parts/list','id'=>$id,'fault_id'=>$fault_id])?>">
        申请配件
    </a>
    <a id="process-btn" class="h-link" href="javascript:void()">
        扫描配件绑定
    </a>
</div>

<script>
    <?php $this->beginBlock('JS_END') ?>
    var mUrl = '<?=$mUrl?>';
    var fault_id = fault_id || <?=$fault_id?>;
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

\app\components\WxjsapiWidget::widget([
    'wx_id'=>$id,
    'apiList'=>['scanQRCode'],
    'jsReady'=>'
        document.querySelector("#process-btn").onclick = function () {
            wx.scanQRCode({
                needResult: 1,  // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                    if( result.indexOf(mUrl) >= 0 ){
                        location = result+"&fault_id="+fault_id;
                    }else{
                        alert("二维码不符合！");
                    }
                }
            });
            return false;
        };'
])
?>