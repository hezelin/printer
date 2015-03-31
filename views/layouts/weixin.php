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
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
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
                        'label' => '微信管理',
                        'items' => [
                           ['label' => '我的公众号', 'url' => Url::to(['weixin/index'])],
                           ['label' => '添加公众号', 'url' => Url::to(['weixin/add'])],
                        ],
                    ],
                    ['label' => '我的主页', 'url' => ['/site/about']],
                    ['label' => '机器管理', 'url' => ['/site/contact']],
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
                    echo TreeMenuWidget::widget(
                        [
                            'items' => [
                                [
                                    'label' => '微信管理',
                                    'items' => [
                                        ['label' => '我的公众号', 'url' => '/weixin/index'],
                                        ['label' => '添加公众号', 'url' => '/weixin/add'],
                                    ],
                                ],
                                [
                                    'label' => '我的账号',
                                    'items' => [
                                        ['label' => '修改密码', 'url' => '/user/reset'],
                                        ['label' => '查看日志', 'url' => '/user/log'],
                                    ],
                                ],
                                [
                                    'label' => '权限管理',
                                    'items' => [
                                        ['label' => '角色管理','url' => '/role/index'],
                                        ['label' => '账户管理','url' => '/account/index'],
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
