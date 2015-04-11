<?php
    use yii\helpers\Url;
    $this->title = $store_setting->store_name;
    if(!$store_setting->status) {echo '本网站暂已关闭！';exit;}
    switch($store_setting->style){
        case '1': $cssfile = 'home-default.css'; break;
        default : $cssfile = 'home-default.css';
    }
    $this->registerCssFile("/css/home/".$cssfile);
?>
<link href="/css/weixin.css" rel="stylesheet">

<!--轮播图-->
<div class="banner" style="margin-top: 2px">

    <div id="wrapper">
        <div id="scroller">
            <ul id="thelist">
                <?php
                $i=0;
                foreach($carousel as $onecarousel) {
                    if(is_file($onecarousel['imgurl'])){
                        $i++;
                ?>
                <li><p><?= $onecarousel['title'] ?></p>
                    <a href="<?= $onecarousel['link']?$onecarousel['link']:'javascript:void(0)' ?>">
                        <img src="/<?= $onecarousel['imgurl'] ?>" style="width: 100%;" /></a><!--图片大小未统一-->
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
<ul class="mainmenu">
    <li><a href="<?= Url::toRoute(['i/machine']) ?>" ><b><img src="/images/tb01.png" /></b><span>我的机器</span></a></li>
    <li><a href="<?= Url::toRoute(['share/score']) ?>" ><b><img src="/images/tb07.png" /></b><span>赚取积分</span></a></li>
    <li><a href="<?= Url::toRoute(['i/service']) ?>" ><b><img src="/images/tb03.png" /></b><span>维修服务</span></a></li>
    <li><a href="<?= Url::toRoute(['share/active']) ?>" ><b><img src="/images/tb08.png" /></b><span>最新活动</span></a></li>
    <li><a href="<?= Url::toRoute(['mall/index']) ?>" ><b><img src="/images/tb05.png" /></b><span>微商城</span></a></li>
    <li><a href="<?= Url::toRoute(['share/game']) ?>" ><b><img src="/images/tb06.png" /></b><span>游戏中心</span></a></li>
</ul>

<div class="copyright" style="margin-top: 20px">技术支持：<?= Yii::$app->name ?> </div>
<!--<div class="copyright"><br /><br />Copyright © 2014-2015 <a href="home/index"><?/*= $weixin->name */?><br /> All rights reserved.</div>-->
