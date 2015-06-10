<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\HomeAsset;

HomeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() //引入资源文件?>

</head>
<body>

<?php $this->beginBody() ?>

<!--网页内容-->
<?= $content ?>

<div class="copyright">技术支持：<?= Yii::$app->name ?></div>
<div style="width: 100%;display: block;line-height:<?=Yii::$app->params['layoutBottomHeight']?>px;height:<?=Yii::$app->params['layoutBottomHeight']?>px;">&nbsp;</div>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>