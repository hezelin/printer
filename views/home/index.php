<?php

use yii\helpers\Url;

$this->title = $setting['store_name'];

$this->registerCssFile('/css/home/'.$setting['style']);
//$this->registerJsFile('/js/iscroll5.js');

?>

<!--轮播图-->
<div id="viewport">
    <div id="wrapper">
        <div id="scroller">
            <?php
                $i=0;
                foreach($setting['carousel'] as $onecarousel) {
                    if(is_file($onecarousel['imgurl'])){
                        $i++;
                ?>
            <div class="slide">
                    <p class="carousel-title"><?= $onecarousel['title'] ?></p>
                    <a href="<?= $onecarousel['link']?$onecarousel['link']:'javascript:void(0)' ?>">
                        <img src="/<?= $onecarousel['imgurl'] ?>" style="width: 100%;" />
                    </a>
            </div>
                <?php
                    }
                }
                if($i == 0)
                echo "<div>暂无任何图片</div>";
                ?>
        </div>
    </div>
</div>

<div id="indicator">
    <div id="dotty"></div>
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

<script>
    <?php $this->beginBlock('JS_END') ?>
    var myScroll;

    function loaded () {

        var count = document.getElementById("scroller").getElementsByTagName("img").length;
        for(i=0;i<count;i++){
            document.getElementById("scroller").querySelectorAll(".slide").item(i).style.cssText = " width:"+document.body.clientWidth+"px;height:"+document.body.clientWidth/2+"px";

            //设置图片显示大小
            document.getElementById("scroller").getElementsByTagName("img").item(i).style.cssText = " width:"+document.body.clientWidth+"px";
            document.getElementById("scroller").getElementsByTagName("img").item(i).style.cssText = " height:"+document.body.clientWidth/2+"px";
        }
        document.getElementById("scroller").style.cssText = " width:"+document.body.clientWidth*count+"px";
        document.getElementById("wrapper").style.cssText = " width:"+document.body.clientWidth+"px;height:"+document.body.clientWidth/2+"px";
        document.getElementById('indicator').style.cssText = "width:"+(count*30-10)+"px;top:"+(document.body.clientWidth/2-35)+"px";


        myScroll = new IScroll('#wrapper', {
            scrollX: true,
            scrollY: false,
            momentum: false,
            snap: true,
            snapSpeed: 400,
            indicators: {
                el: document.getElementById('indicator'),
                resize: false
            }
        });

//        轮播循环
        var nn=0;
        setInterval(function () {
            nn ++;
            if(nn == count){
                myScroll.goToPage(0,0,1000);
                nn =0;
            }else
                myScroll.next();
//            console.log( myScroll.currentPage );
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', loaded, false);


    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>