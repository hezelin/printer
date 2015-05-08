<?php
use yii\helpers\Url;
use app\components\CarouselWidget;

$this->title = $setting['store_name'];

$this->registerCssFile('/css/home/'.$setting['style']);
//$this->registerJsFile('/js/iscroll5.js');

?>

<?= CarouselWidget::widget(['data'=>$setting['carousel']]) ?>
<!--首页菜单-->
<ul id="main-menu">
    <li><a href="<?= Url::toRoute(['i/machine','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb01.png" /></b><span>我的机器</span></a></li>
    <li><a href="<?= Url::toRoute(['share/score','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb07.png" /></b><span>赚取积分</span></a></li>
    <li><a id="scan-btn" href="javascript:void(0);" ><b><img src="/images/tb03.png" /></b><span>扫描</span></a></li>
    <li><a href="<?= Url::toRoute(['mall/index','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb05.png" /></b><span>微商城</span></a></li>
    <li><a href="<?= Url::toRoute(['share/active','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb08.png" /></b><span>最新活动</span></a></li>
    <li><a href="<?= Url::toRoute(['share/game','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb06.png" /></b><span>游戏中心</span></a></li>
</ul>

<?php
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$setting['wx_id'],
    'apiList'=>['scanQRCode'],
    'jsReady'=>'
    document.querySelector("#scan-btn").onclick = function () {
        wx.scanQRCode({
            needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
            var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
        }
        });
    };'
])
?>