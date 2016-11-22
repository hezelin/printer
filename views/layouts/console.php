<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\WeixinAsset;

use yii\widgets\Breadcrumbs;
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
                'brandUrl' => '/console/view',
                'brandOptions' => [
                    'style' => 'color:#ffffff',
                ],
                'options' => [
                    'class' => 'navbar-fixed-top',
                    'id' => 'my-header-nav',
                ],
                'innerContainerOptions' => [
                    'class' => 'container-fluid'
                ]
            ]);

        echo Html::beginForm('/service/new-call','get',['class'=>'navbar-form navbar-left','role'=>'search']);
        echo Html::beginTag('div',['class'=>'form-group']);
        echo Html::textInput('machine_id','',['class'=>'form-control','placeholder'=>'输入机器编号,可省略']);
        echo Html::endTag('div');
        echo Html::submitButton('电话报修',['class'=>'btn btn-primary','style'=>'margin-left:15px']);
        echo Html::endForm();

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    Yii::$app->user->isGuest ?
                        ['label' => '登录', 'url' => ['/auth/login']]:
                        [
                            'label' => Yii::$app->user->identity->username,
                            'url' => '#',
                            'items' => [
                                ['label'=>'我的公众号','url'=>['/weixin/index']],
                                ['label'=>'修改密码','url'=>['/auth/reset']],
                                ['label'=>'退出','url' => ['/auth/logout'], 'linkOptions' => ['data-method' => 'post']],
                            ]
                        ],
                ],
            ]);
            NavBar::end();
        ?>
        <div class="container-fluid">
            <div class="row my-content">
                <div class="col-sm-3 col-md-2">
                    <?php
                    echo SideNav::widget([
                        'type' => SideNav::TYPE_DEFAULT,
                        'containerOptions'=>['id' => 'my-left-nav'],
                        'indItem' => '-',
                        'items' => [
                            [
                                'label' => '工作任务',
                                'url' => ['/console/view'],
                                'icon' => 'tasks',
                            ],
                            [
                                'label' => '数据统计',
                                'url' => ['/console/analyze'],
                                'icon' => 'stats',
                                'active' => substr( Yii::$app->controller->getRoute(),0,strpos(Yii::$app->controller->getRoute(),'/')) == 'charts' || Yii::$app->controller->getRoute()=='console/analyze'
                            ],
                            [
                                'label' => '机器管理',
                                'icon' => 'print',
                                'items' => [
                                    ['label' => '机器资料', 'url' => ['/machine/list'],
                                        'active' => substr( Yii::$app->controller->getRoute(),0,strpos(Yii::$app->controller->getRoute(),'/')) == 'machine' || Yii::$app->controller->getRoute()=='charts/machine-rental',
                                    ],
                                    ['label' => '租赁方案', 'url' => ['/rent-project/list'],
                                        'active' => substr( Yii::$app->controller->getRoute(),0,strpos(Yii::$app->controller->getRoute(),'/')) == 'rent-project',
                                    ],
                                ],
                            ],
                            [
                                'label' => '租赁管理',
                                'icon' => 'retweet',
                                'items' => [
                                    ['label' => '租赁申请','url' => ['/admin-rent/apply']],
                                    ['label' => '租赁资料','url' => ['/admin-rent/list'],
                                        'active' => in_array(Yii::$app->controller->getRoute(),['admin-rent/list','admin-rent/update','admin-rent/pass']),
                                    ],
                                    ['label' => '收租记录','url' => ['/charge/list'],
                                        'active' => substr( Yii::$app->controller->getRoute(),0,strpos(Yii::$app->controller->getRoute(),'/')) == 'charge',
                                    ],
                                    ['label' => '地图分布','url' => ['/map/rent']],
                                ],
                            ],
                            [
                                'label' => '维修管理',
                                'icon' => 'wrench',
                                'items' => [
                                    ['label' => '待分配维修','url' => ['/service/index']],
                                    ['label' => '维修资料','url' => ['/service/list'],
                                        'active' => in_array(Yii::$app->controller->getRoute(),['service/list','service/process']),
                                    ],
                                    ['label' => '电话报修','url' => ['/service/call']],
                                    ['label' => '维修配件管理', 'url' => ['/shop/adminparts/list']],

                                ],
                            ],
                            [
                                'label' => '微官网',
                                'icon' => 'home',
                                'items' => [
                                    ['label' => '店铺装修', 'url' => ['/home/fitment']],
                                    ['label' => '店铺设置', 'url' => ['/home/setting']],
                                ],
                            ],
                            [
                                'label' => '二维码管理',
                                'icon' => 'qrcode',
                                'items' => [
                                    ['label' => '积分二维码','url' => ['/code/score']],
                                    [
                                        'label' => '机器二维码','url' => ['/code/index'],
                                        'active' => in_array(Yii::$app->controller->getRoute(),['code/index','code/machine','code/machineall']),
                                    ],
                                    ['label' => '预设机器二维码','url' => ['/code/ahead']],
//                                    ['label' => '配件二维码','url' => ['/code/parts']],
                                    ['label' => '维修员绑定码','url' => ['/code/binding']],
                                ],
                            ],
                            [
                                'label' => '商城管理',
                                'icon' => 'shopping-cart',
                                'items' => [
                                    ['label' => '耗材列表','url' => ['/shop/backend/list']],
                                    ['label' => '订单管理', 'url' => ['/shop/adminorder/check']],
                                    ['label' => '轮播图设置', 'url' => ['/shop/admin-home/carousel']],
                                ],
                            ],
                            [
                                'label' => '积分管理',
                                'icon' => 'gift',
                                'items' => [
                                    ['label' => '赠送积分','url' => ['/admin-score/send']],
                                    ['label' => '积分操作记录','url' => ['/admin-score/log']],
                                ],
                            ],
                            /*[-
                                'label' => '用户管理',
                                'icon' => 'user',
                                'items' => [
                                    ['label' => '用户列表','url' => ['/wxuser/list']],
                                    ['label' => '维修员列表','url' => ['/staff/list']],
                                ]
                            ],*/

                            ['label' => '微信用户','icon' => 'user','url' => ['/wxuser/list']],
                            ['label' => '维修员资料','icon' => 'magnet', 'url' => ['/staff/list']],
                            [
                                'label' => '通知管理',
                                'icon' => 'bell',
                                'items' => [
                                    ['label' => '发送通知','url' => ['/notify/send']],
                                    ['label' => '通知日志','url' => ['/notify/list']]
                                ]
                            ],
                            ['label' => '最新活动','icon' => 'flag', 'url' => ['/notify/activity']],

                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-9 col-md-10">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
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
