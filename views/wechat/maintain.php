<?php
use yii\helpers\Url;
use app\components\CarouselWidget;

$this->title = '维修主页';

$this->registerCssFile('/css/home/'.$setting['style']);
//$this->registerJsFile('/js/iscroll5.js');

?>

<?= CarouselWidget::widget(['data'=>$setting['carousel']]) ?>
<!--首页菜单-->
<ul id="main-menu">
    <li><a href="<?= Url::toRoute(['m/position','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb01.png" /></b><span>提交位置</span></a></li>
    <li><a href="<?= Url::toRoute(['m/task','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb07.png" /></b><span>维修任务</span></a></li>
    <li><a href="<?= Url::toRoute(['m/record','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb03.png" /></b><span>维修记录</span></a></li>
    <li><a href="<?= Url::toRoute(['m/evaluate','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb08.png" /></b><span>我的评价</span></a></li>
    <li><a href="<?= Url::toRoute(['m/index','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb05.png" /></b><span>我的业绩</span></a></li>
    <li><a href="<?= Url::toRoute(['m/notice','id'=>$setting['wx_id']]) ?>" ><b><img src="/images/tb06.png" /></b><span>最新通知</span></a></li>
</ul>
