<?php

use yii\helpers\Url;

$this->title = $setting['store_name'];

$this->registerCssFile('/css/home/'.$setting['style']);
$this->registerJsFile('/js/home/iscroll.js');

?>

<!--轮播图-->
<div class="banner">

    <div id="wrapper">
        <div id="scroller">
            <ul id="thelist">
                <?php
                $i=0;
                foreach($setting['carousel'] as $onecarousel) {
                    if(is_file($onecarousel['imgurl'])){
                        $i++;
                ?>
                <li>
                    <p><?= $onecarousel['title'] ?></p>
                    <a href="<?= $onecarousel['link']?$onecarousel['link']:'javascript:void(0)' ?>">
                        <img src="/<?= $onecarousel['imgurl'] ?>" style="width: 100%;" />
                    </a><!--图片大小未统一-->
                </li>
                <?php
                    }
                }
                if($i == 0)
                echo "<div>暂无任何图片</div>";
                ?>
            </ul>
        </div>
    </div>

    <div id="nav">
        <ul id="indicator">
            <li class="active"></li>
            <?php for($j=0;$j<$i-1;$j++) echo "<li></li>"?>
        </ul>
    </div>

</div>

<!--首页菜单-->
<ul id="main-menu">
    <li><a href="<?= Url::toRoute(['i/machine']) ?>" ><b><img src="/images/tb01.png" /></b><span>我的机器</span></a></li>
    <li><a href="<?= Url::toRoute(['share/score']) ?>" ><b><img src="/images/tb07.png" /></b><span>赚取积分</span></a></li>
    <li><a href="<?= Url::toRoute(['i/service']) ?>" ><b><img src="/images/tb03.png" /></b><span>维修服务</span></a></li>
    <li><a href="<?= Url::toRoute(['share/active']) ?>" ><b><img src="/images/tb08.png" /></b><span>最新活动</span></a></li>
    <li><a href="<?= Url::toRoute(['mall/index']) ?>" ><b><img src="/images/tb05.png" /></b><span>微商城</span></a></li>
    <li><a href="<?= Url::toRoute(['share/game']) ?>" ><b><img src="/images/tb06.png" /></b><span>游戏中心</span></a></li>
</ul>
