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

<!--左下角导航菜单-->
<div class="top_bar" style="-webkit-transform:translate3d(0,0,0)">
    <nav>
        <ul id="top_menu" class="top_menu">
            <input type="checkbox" id="plug-btn" class="plug-menu themeStyle" style="background-color:#1AB8C5;background-image:url('/images/plug.png');border:0px;">
            <li class="themeStyle out"> <a href="<?= Url::toRoute(['i/score']) ?>"><img src="/images/plugmenu3.png"><label>我的积分</label></a></li>
            <li class="themeStyle out"> <a href="<?= Url::toRoute(['i/machine']) ?>"><img src="/images/plugmenu1.png"><label>我的机器</label></a></li>
            <li class="themeStyle out"> <a href="<?= Url::toRoute(['i/index']) ?>"><img src="/images/plugmenu6.png"><label>个人中心</label></a></li>
        </ul>
    </nav>
</div>
<div id="plug-wrap" style="display: none;" ></div>

<!--网页内容-->
<?= $content ?>

<div class="copyright">
    技术支持：<?= Yii::$app->name ?>
</div>

<?php $this->endBody() ?>

<script>
    $(function(){
        $(".plug-menu").click(function(){
            var li = $(this).parents('ul').find('li');
            if(li.attr("class") == "themeStyle on"){
                li.removeClass("themeStyle on");
                li.addClass("themeStyle out");
            }else{
                li.removeClass("themeStyle out");
                li.addClass("themeStyle on");
            }
        });
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
