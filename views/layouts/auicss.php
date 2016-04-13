<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AuicssAsset;

AuicssAsset::register($this);
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
<?= $content ?>

<div class="copyright aui-text-default">技术支持：<?= Yii::$app->name ?></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>