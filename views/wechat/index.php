<?php
use yii\helpers\Url;
use app\components\CarouselWidget;
use app\components\FixedmenuWidget;
use app\assets\HomeAsset;
HomeAsset::register($this);

$this->title = $setting['store_name'];
$this->registerCssFile('/css/swiper/swiper.min.css',['depends'=>['app\assets\AuicssAsset']]);
$this->registerCssFile('/css/font-icon/im1/font-icon.css',['depends'=>['app\assets\AuicssAsset']]);

?>

<style type="text/css">
    .rhome{
        font-family:'微软雅黑'  !important;
        max-width:640px;
        min-width:320px;
        margin:0 auto;
        background: #f8f8f8;
    }
    img{
        vertical-align:bottom;
    }

    /* aui */
    .aui-mg-b-15{
        margin-bottom:15px;
    }
    .aui-grid-nine li{
        padding-top:35px;
        padding-bottom:35px;
    }
    .aui-grid-nine li p{
        color:#000;
    }
    .aui-grid-nine:after, .aui-grid-nine li:after{
        border-color:#eee;
    }
    .aui-grid-nine li .aui-iconfont{
        font-size: 32px;
        margin-bottom: 15px;
    }
    .aui-text-cblue{
        color:#33b5e5;
    }
    .aui-padded-5-10{
        padding:5px 10px;
    }

    /*swiper*/
    .swiper-pagination-bullet{
        border:2px solid #fff;
        background: none;
        opacity: 1;
    }
    .swiper-pagination-bullet-active{
        background: #fff;
    }
    .swiper-pagination-bullet{
        width:10px;
        height:10px;
    }

    /*页尾自定义*/
    .wrap{
        position: fixed;
        bottom: 0;
        max-width:640px;
        min-width:320px;
        width:100%;
        background:#eaeaea;
        font-size:15px;
        box-shadow:0px -2px 4px #d9d9d9;
    }
    .border-r{
        border:1px solid #999;
        border-radius:20px;
        color:#000;
    }
    .bgcolor-r-active{
        border:1px solid #33b5e5;
        color:#fff;
        background: #33b5e5;
        border-radius:20px;
    }
</style>
<header class="header_wechat">
    <div id="flash_rhome" class="aui-mg-b-15">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <a href="#"><img src="/images/flash.png" width="100%"></a>
                </div>
                <div class="swiper-slide">
                    <a href="#"><img src="/images/flash.png" width="100%"></a>
                </div>
                <div class="swiper-slide">
                    <a href="#"><img src="/images/flash.png" width="100%"></a>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</header>
<div class="main_wechat">
    <div class="aui-content">
        <ul class="aui-grid-nine">
            <a class="aui-block" href="<?= Url::toRoute(['/user/i/machine','id'=>$setting['wx_id']]) ?>">
                <li class="aui-col-xs-4 aui-text-center">
                    <span class="aui-iconfont icon-uniE900 aui-text-cblue"></span>
                    <p>我的机器</p>
                </li>

            </a>
            <a class="aui-block" href="<?= Url::toRoute(['share/scheme','id'=>$setting['wx_id']]) ?>">
                <li class="aui-col-xs-4 aui-text-center">
                    <span class="aui-iconfont icon-uniE903 aui-text-cblue"></span>
                    <p>赚取积分</p>
                </li>
            </a>
            <a class="aui-block" href="javascript:void(0);">
                <li class="aui-col-xs-4 aui-text-center">
                    <span class="aui-iconfont icon-uniE901 aui-text-cblue"></span>
                    <p>扫描报修</p>
                </li>
            </a>
            <a class="aui-block" href="<?= Url::toRoute(['shop/item/list','id'=>$setting['wx_id']]) ?>">
                <li class="aui-col-xs-4 aui-text-center">
                    <span class="aui-iconfont icon-uniE902 aui-text-cblue"></span>
                    <p>微商城</p>
                </li>
            </a>
            <a class="aui-block" href="<?= Url::toRoute(['share/active','id'=>$setting['wx_id']]) ?>">
                <li class="aui-col-xs-4 aui-text-center">
                    <span class="aui-iconfont icon-uniE902 aui-text-cblue"></span>
                    <p>最新活动</p>
                </li>
            </a>
            <a class="aui-block" href="<?= Url::toRoute(['i/index','id'=>$setting['wx_id']]) ?>">
                <li class="aui-col-xs-4 aui-text-center">
                    <span class="aui-iconfont icon-uniE902 aui-text-cblue"></span>
                    <p>个人中心</p>
                </li>
            </a>
        </ul>
    </div>
</div>
<footer class="footer_wechat">
    <div class="wrap aui-padded-5-10">
        <div class="aui-col-xs-6 aui-text-center aui-padded-5-10">
            <a href="<?= Url::toRoute(['i/index','id'=>$setting['wx_id']]) ?>" class="aui-padded-5 border-r aui-block">
                <span class="icon-uniE905"></span>
                <span>个人中心</span>
            </a>
        </div>
        <div class="aui-col-xs-6 aui-text-center aui-padded-5-10">
            <a href="javascript:void(0);" class="aui-padded-5 border-r aui-block bgcolor-r-active">
                <span class="icon-uniE905" ></span>
                <span>客服</span>
            </a>
        </div>
    </div>
</footer>

<script>
    <?php $this->beginBlock('JS_END') ?>
        (function(){
            new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                autoplay:5000,
                loop:true,
                autoplayDisableOnInteraction:false,
                speed:500,
            })
        })()
    <?php $this->endBlock();?>
</script>


<?php $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY); ?>

<?php
\app\components\WxjsapiWidget::widget([
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
])
?>