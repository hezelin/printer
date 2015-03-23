<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
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

    <style>
        /*
            暂时性
        */
        .tree-menu{
            border: 1px solid #dddddd;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }
        .tree-menu .panel-heading {
        }
        .tree-menu .panel-body{
            padding: 0 !important;
        }
        .tree-menu .nav-pills > li > a{
            border-radius: 0 !important;
        }
        .tree-menu  .panel{
            border-radius: 0 !important;
            box-shadow: none !important;
            border: none ;
            margin: 0px !important;
            border-bottom: 1px solid #DDDDDD;
        }
    </style>

</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
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

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>


            <div class="row">
                <div class="col-sm-3">
                    <div id="w0" class="panel-group tree-menu">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapse-toggle" href="#w0-collapse1" data-toggle="collapse" data-parent="#w0">
                                        微信管理 <b class="caret"></b>
                                    </a>
                                </h4>
                            </div>
                            <div id="w0-collapse1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul class="nav nav-pills nav-stacked">
                                        <li role="presentation" class="active"><a href="#">Home</a></li>
                                        <li role="presentation"><a href="#">Profile</a></li>
                                        <li role="presentation"><a href="#">Messages</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapse-toggle" href="#w0-collapse2" data-toggle="collapse" data-parent="#w0">
                                        机器管理 <b class="caret"></b>
                                    </a>
                                </h4>
                            </div>
                            <div id="w0-collapse2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul class="nav nav-pills nav-stacked">
                                        <li role="presentation" class="active"><a href="#">Home</a></li>
                                        <li role="presentation"><a href="#">Profile</a></li>
                                        <li role="presentation"><a href="#">Messages</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
