<?php
use yii\helpers\Url;
use app\components\CarouselWidget;
use app\components\FixedmenuWidget;

$this->title = $setting['store_name'];

//$this->registerCssFile('/css/home/'.$setting['style']);

?>

<?php /*echo FixedmenuWidget::widget(['wx_id'=>$setting['wx_id'],'phone'=>$setting['phone']])*/ ?>

<?= CarouselWidget::widget(['data'=>$setting['carousel']]) ?>


<div class="aui-content" id="home-fault">
    <ul class="aui-list-view aui-grid-view">
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['i/machine','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="iconfont icon-icondayinji aui-color"></span>
                <h2 class="aui-img-body aui-text-default">我的机器</h2>
            </a>
        </li>

        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a id="scan-btn" href="javascript:void(0);" class="link-wrap">
                <span class="iconfont icon-saomiao aui-color"></span>
                <h2 id="code-loading" class="aui-img-body aui-text-default">加载中...</h2>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['shop/item/list','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="iconfont icon-p-shop aui-color"></span>
                <h2 class="aui-img-body aui-text-default">微商城</h2>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['share/scheme','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="iconfont icon-icon305 aui-color"></span>
                <h2 class="aui-img-body aui-text-default">赚取积分</h2>
            </a>
        </li>
        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['i/index','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="iconfont icon-gerenziliao aui-color"></span>
                <h2 class="aui-img-body aui-text-default">个人中心</h2>
            </a>
        </li>

        <li class="aui-list-view-cell aui-img aui-col-xs-6">
            <a href="<?= Url::toRoute(['share/active','id'=>$setting['wx_id']]) ?>" class="link-wrap">
                <span class="iconfont icon-huodong aui-color"></span>
                <h2 class="aui-img-body aui-text-default">最新活动</h2>
            </a>
        </li>

    </ul>
</div>

<?php
/*\app\components\WxjsapiWidget::widget([
    'wx_id'=>$setting['wx_id'],
    'apiList'=>['scanQRCode'],
    'jsReady'=>'
    document.querySelector("#code-loading").innerHTML = "扫码报修";
    document.querySelector("#scan-btn").onclick = function () {
        wx.scanQRCode({
            needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
            var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
        }
        });
    };'
])*/
?>