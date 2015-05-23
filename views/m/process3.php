<?php
use yii\helpers\Url;
$this->title = '维修任务';
?>
<style>
    body{ background-color: #ffffff !important;}
</style>

<div class="h-box">
    <div class="h-title h-gray h-hr">故障信息</div>
    <div class="h-box-row h-hr">
        <div class="h-left">
            <img class="h-img" src="<?=$model['fault_cover']?>" />
        </div>
        <div class="h-right">
            <h4 class="h-row-1 h-hr">故障类型：<?=\app\models\ConfigBase::getFaultStatus($model['fault_type'])?></h4>
            <p class="h-row-2"><?=$model['desc']?></p>
        </div>
    </div>
</div>

<div class="h-box">
    <div class="h-title h-gray h-hr">客户资料</div>
    <div class="h-row">
        <div class="h-left-text">客户信息</div>
        <div class="h-right-text"><?= $model['name'],',<a class="h-tel" href="tel:',$model['phone'],'">',$model['phone']?></a></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">客户地区</div>
        <div class="h-right-text"><?= $region?></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">客户地址</div>
        <div class="h-right-text"><?= $model['address']?></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">申请时间</div>
        <div class="h-right-text"><?= date('Y年m月d H:i',$model['add_time'])?></div>
    </div>

</div>

<?=$btnHtml?>

<script>
<?php $this->beginBlock('JS_END') ?>
    var isScan = 0;
    var mUrl = '<?=$mUrl?>';
    var hasClick = 0;

    function process() {
        if (hasClick == 1)
            return false;

        hasClick = 1;
        var $this = $('#process-btn');

        var href = $this.attr('href');
        var status = $this.attr('data-status');
        $this.addClass('h-loading');
        if ( $this.attr('data-ajax') == "0") {
            window.location.href = href;
            return false;
        }
        $.post(
            href,
            {'status': status},
            function (res) {
                if (res.status == 1) {
                    $this.attr({
                        'data-status': res.dataStatus,
                        'href': res.href,
                        'data-ajax': res.dataAjax
                    }).text(res.btnText);
                } else
                    alert(res.msg);
                hasClick = 0;
                $this.removeClass('h-loading');
            }, 'json'
        );
    }
<?php $this->endBlock();?>
</script>
<?php
    \app\assets\ZeptoAsset::register($this);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

    \app\components\WxjsapiWidget::widget([
        'wx_id'=>$model['wx_id'],
        'apiList'=>['scanQRCode'],
        'jsReady'=>'
        document.querySelector("#process-btn").onclick = function () {
            if(isScan == 1){
                process();
                return false;
            }
            wx.scanQRCode({
                needResult: 1,  // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
//                alert( result);
                if( mUrl === res.resultStr ){
                    isScan = 1;
                    process();
                }
            }
            });
            return false;
        };'
    ])
?>