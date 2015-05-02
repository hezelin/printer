<?php
use yii\helpers\Url;
use app\components\CarouselWidget;

$this->title = $setting['store_name'];

$this->registerCssFile('/css/home/'.$setting['style']);
//$this->registerJsFile('/js/iscroll5.js');

?>

<?= CarouselWidget::widget(['data'=>$setting['carousel']]) ?>
<!--首页菜单-->
<ul id="main-menu">
    <li><a href="<?= Url::toRoute(['i/machine']) ?>" ><b><img src="/images/tb01.png" /></b><span>我的机器</span></a></li>
    <li><a href="<?= Url::toRoute(['share/score']) ?>" ><b><img src="/images/tb07.png" /></b><span>赚取积分</span></a></li>
    <li><a href="<?= Url::toRoute(['i/service']) ?>" ><b><img src="/images/tb03.png" /></b><span>维修服务</span></a></li>
    <li><a href="<?= Url::toRoute(['share/active']) ?>" ><b><img src="/images/tb08.png" /></b><span>最新活动</span></a></li>
    <li><a href="<?= Url::toRoute(['mall/index']) ?>" ><b><img src="/images/tb05.png" /></b><span>微商城</span></a></li>
    <li><a href="<?= Url::toRoute(['share/game']) ?>" ><b><img src="/images/tb06.png" /></b><span>游戏中心</span></a></li>
</ul>
