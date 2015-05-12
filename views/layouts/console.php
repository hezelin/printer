<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\WeixinAsset;
use app\components\TreeMenuWidget;


/* @var $this \yii\web\View */
/* @var $content string */

WeixinAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->session['wechat']['name'],
                'brandUrl' => Url::toRoute( ['console/view','id'=>Yii::$app->session['wechat']['id'] ]),
                'brandOptions' => [
                    'style' => 'color:#ffffff',
                ],
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
                'innerContainerOptions' => [
                    'class' => 'container-fluid'
                ]
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    [
                        'label' => '返回实例',
                        'url' => ['/weixin/index'],
                    ],
                ],
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
<P style="height: 40px">&nbsp;</P>
        <div class="container-fluid">
            <div class="row">
                <?php
//                    $aa = Url::toRoute( ['console/view','id'=>Yii::$app->session['wechat']['id'] );
                    echo TreeMenuWidget::widget(
                        [
                            'items' => [
                                [
                                    'label' => '实例详情',
                                    'url' => [ Url::toRoute( ['console/view','id'=>Yii::$app->session['wechat']['id'] ]) ],
                                    'route' => '/console/view',
                                ],
                                [
                                    'label' => '微官网',
                                    'items' => [
                                        ['label' => '店铺装修', 'url' => '/home/fitment'],
                                        ['label' => '店铺设置', 'url' => '/home/setting'],
                                    ],
                                ],
                                [
                                    'label' => '机器管理',
                                    'items' => [
                                        ['label' => '机器列表', 'url' => '/machine/list'],
                                        ['label' => '添加机器', 'url' => '/machine/add'],
                                        ['label' => '机器状态', 'url' => '/machine/status'],
                                    ],
                                ],
                                [
                                    'label' => '二维码管理',
                                    'items' => [
                                        ['label' => '积分二维码','url' => '/code/score'],
                                        ['label' => '生成机器码','url' => '/code/index'],
                                        ['label' => '维修员绑定码','url' => '/code/binding'],
                                    ],
                                ],
                                [
                                    'label' => '租借管理',
                                    'items' => [
                                        ['label' => '租借统计','url' => '/adminrent/index'],
                                        ['label' => '租借列表','url' => '/adminrent/list'],
                                        ['label' => '租借申请','url' => '/adminrent/apply'],
                                    ],
                                ],
                                [
                                    'label' => '维修管理',
                                    'items' => [
                                        ['label' => '待分配维修','url' => '/service/index'],
                                        ['label' => '维修列表','url' => '/service/list'],
                                    ],
                                ],
                                [
                                    'label' => '维修员管理',
                                    'items' => [
                                        ['label' => '工作报表','url' => '/staff/index'],
                                        ['label' => '维修员列表','url' => '/staff/list'],
                                    ],
                                ],
                                [
                                    'label' => '耗材管理',
                                    'items' => [
                                        ['label' => '耗材列表','url' => '/item/list'],
                                        ['label' => '录入耗材','url' => '/item/add'],
                                    ],
                                ],
                            ],
                        ]);
                ?>
                <div class="col-sm-2 col-md-2">
                    &nbsp;
                </div>
                <div class="col-sm-10 col-md-10">
                    <p style="height: 20px">&nbsp;</p>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
