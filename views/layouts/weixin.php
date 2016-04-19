<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\WeixinAsset;
use kartik\sidenav\SideNav;

WeixinAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!--[if lte IE 8]><div id="letskillie6">
        <div class="alert" style="position: fixed;left: 50%;margin-left: -470px;width: 918px;z-index: 9999;_position: absolute;_top: expression(eval(document.documentElement.scrollTop));">
            <div class="content">
                <a class="close" rel="nofollow" id="letskillie6_close" href="####"></a>
                <p style="font-size: 14px;">
                    您正在使用较低版本的IE浏览器浏览网页，推荐您使用<a target="_blank" rel="nofollow" class="ie8" href="http://se.360.cn/"><strong>360浏览器</strong></a>或其他浏览器，本站将能为您提供更好的服务。
                </p>
                <p class="browsers" style="text-align: center;">
                    <a style="margin-right:10px;" rel="nofollow" target="_blank" class="firefox" href="http://www.mozillaonline.com/">Firefox</a>
                    <a style="margin-right:10px;" rel="nofollow" target="_blank" class="chrome" href="http://dlsw.baidu.com/sw-search-sp/soft/9d/14744/ChromeStandalone_V45.0.2454.93_Setup.1442372894.exe">Chrome</a>
                    <a style="margin-right:10px;" rel="nofollow" target="_blank" class="sougo" href="http://se.360.cn/">360浏览器</a>
                </p>
            </div>
        </div>
        <p style="height:53px"></p>
    </div>
    <script type="text/javascript">
        (function() {
            var letskillie6 = document.getElementById('letskillie6');
            letskillie6.onclick = function() {
                removeElement(this);
            }
            function removeElement(_element) {
                var _parentElement = _element.parentNode;
                if (_parentElement) {
                    _parentElement.removeChild(_element);
                }
            }

        })()
    </script><![endif]-->

</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => '#',
                'brandOptions' => [
                    'style' => 'color:#ffffff',
                ],
                'options' => [
                    'class' => 'navbar-fixed-top',
                ],
                'innerContainerOptions' => [
                    'class' => 'container-fluid',
                    'id' => 'my-header-nav',
                ]
            ]);

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    Yii::$app->user->isGuest ?
                        ['label' => '登录', 'url' => ['/auth/login']]:
                        ['label' => '退出 (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/auth/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ],
                ],
            ]);
            NavBar::end();
        ?>

    <div class="container-fluid">
        <div class="row my-content">

            <div class="col-sm-3 col-md-2">
                <?php
                $item = [
                    [
                        'label' => '管理后台',
                        'url' => ['/console/view'],
                        'icon' => 'th',
                    ],
                    [
                        'label' => '微信管理',
                        'icon' => 'comment',
                        'items' => [
                            ['label' => '我的公众号', 'url' => ['/weixin/index']],
                            ['label' => '添加公众号', 'url' => ['/weixin/add']],
                        ],
                    ],
                    [
                        'label' => '我的账号',
                        'icon' => 'user',
                        'items' => [
                            ['label' => '修改密码', 'url' => ['/user/reset']],
//                                        ['label' => '查看日志', 'url' => '/user/log'],
                        ],
                    ],
                ];

                if(Yii::$app->user->id == 4)
                    array_push($item,['label' => '报名表', 'url' => ['/console/zuji-apply'],'icon'=>'stats']);

                echo SideNav::widget([
                    'type' => SideNav::TYPE_DEFAULT,
                    'containerOptions'=>['id' => 'my-left-nav'],
                    'indItem' => '-',
                    'items' => $item
                ]);
                ?>
            </div>
            <div class="col-sm-9 col-md-10">
                <p style="height: 20px">&nbsp;</p>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
<script>
    $(function(){
        var hei = $(window).height();
        var wid = $('#my-left-nav').width();
        $('#my-left-nav').css({
            height:hei-51,
            width:wid,
            position:'fixed',
            top:51,
            left:0
        });
        $('#my-header-nav .navbar-brand').css({
            width:wid
        });
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
