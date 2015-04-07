<?php
use yii\web\View;
$this->title = '蓝标打印机微网站';
//$this->registerJsFile("/js/weixin/jquery.min.js",['position' => View::POS_HEAD]);
//$this->registerJsFile("/js/weixin/iscroll.js",['position' => View::POS_HEAD]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$this->title ?></title>

    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta charset="utf-8">

    <link href="/css/weixin/style.css" rel="stylesheet" type="text/css" />
    <link href="/css/weixin/iscroll.css" rel="stylesheet" type="text/css" />
    <style>
        .banner img {width: 100%;}
    </style>

    <script type="text/javascript" src="/js/weixin/jquery.min.js"></script>
    <script type="text/javascript" src="/js/weixin/iscroll.js"></script>
    <script type="text/javascript">
        var myScroll;
        function loaded(){
            myScroll = new iScroll('wrapper', {
                snap: true,
                momentum: false,
                hScrollbar: false,
                onScrollEnd: function () {
                    document.querySelector('#indicator > li.active').className = '';
                    document.querySelector('#indicator > li:nth-child(' + (this.currPageX+1) + ')').className = 'active';
                }
            });
        }
        document.addEventListener('DOMContentLoaded', loaded, false);
    </script>

</head>

<body>

<!--左下角导航菜单-->
<style type="text/css">
    body { margin-bottom:60px !important; }
    ul, li { list-style:none; margin:0; padding:0 }
    #plug-wrap { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0); z-index:800; transition: all 100ms ease-out; -webkit-transition: all 100ms ease-out; }
    .top_bar { position:fixed; bottom:0; left:0px; z-index:900; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-family: Helvetica, Tahoma, Arial, Microsoft YaHei, sans-serif; }
    .plug-menu { -webkit-appearance:button; display:inline-block; width:36px; height:36px; border-radius:36px; position: absolute; bottom:17px; left: 17px; z-index:999; box-shadow: 0 0 0 4px #FFFFFF, 0 2px 5px 4px rgba(0, 0, 0, 0.25); background-color: #B70000; -webkit-transition: -webkit-transform 200ms; -webkit-transform:rotate(1deg); color:#fff; background-image:url('plug.png'); background-repeat: no-repeat; -webkit-background-size: 80% auto; background-size: 80% auto; background-position: center center; }
    .plug-menu:before { font-size:20px; margin:9px 0 0 9px; }
    .plug-menu:checked { -webkit-transform:rotate(135deg); }
    .top_menu { margin-left: -45px; }
    .top_menu>li { min-width: 86px; padding: 0 10px; height:32px; border-radius:32px; box-shadow: 0 0 0 3px #FFFFFF, 0 2px 5px 3px rgba(0, 0, 0, 0.25); background:#B70000; margin-bottom: 23px; margin-left: 23px; z-index:900; transition: all 200ms ease-out; -webkit-transition: all 200ms ease-out; }
    .top_menu>li:last-child { margin-bottom: 80px; }
    .top_menu>li a { color:#fff; font-size:20px; display: block; height: 100%; line-height: 33px; text-indent:26px; text-decoration:none; position:relative; font-size:16px; text-overflow:ellipsis; white-space:nowrap; overflow:hidden; }
    .top_menu>li a img { display: block; width: 24px; height: 24px; text-indent: -999px; position: absolute; top: 50%; left: 10px; margin-top: -13px; margin-left: -12px; }
    .top_menu>li.on:nth-of-type(1) {
        -webkit-transform: translate(45px, 0) rotate(0deg);
        transition: all 700ms ease-out;
        -webkit-transition: all 700ms ease-out;
    }
    .top_menu>li.on:nth-of-type(2) {
        -webkit-transform: translate(45px, 0) rotate(0deg);
        transition: all 600ms ease-out;
        -webkit-transition: all 600ms ease-out;
    }
    .top_menu>li.on:nth-of-type(3) {
        -webkit-transform: translate(45px, 0) rotate(0deg);
        transition: all 500ms ease-out;
        -webkit-transition: all 500ms ease-out;
    }
    .top_menu>li.on:nth-of-type(4) {
        -webkit-transform: translate(45px, 0) rotate(0deg);
        transition: all 400ms ease-out;
        -webkit-transition: all 400ms ease-out;
    }
    .top_menu>li.on:nth-of-type(5) {
        -webkit-transform: translate(45px, 0) rotate(0deg);
        transition: all 300ms ease-out;
        -webkit-transition: all 300ms ease-out;
    }
    .top_menu>li.on:nth-of-type(6) {
        -webkit-transform: translate(45px, 0) rotate(0deg);
        transition: all 200ms ease-out;
        -webkit-transition: all 200ms ease-out;
    }

    /**/
    .top_menu>li.out:nth-of-type(1) {
        -webkit-transform: translate(45px, 280px) rotate(0deg);
        transition: all 600ms ease-out;
        -webkit-transition: all 600ms ease-out;
    }
    .top_menu>li.out:nth-of-type(2) {
        -webkit-transform: translate(45px, 235px) rotate(0deg);
        transition: all 500ms ease-out;
        -webkit-transition: all 500ms ease-out;
    }
    .top_menu>li.out:nth-of-type(3) {
        -webkit-transform: translate(45px, 190px) rotate(0deg);
        transition: all 400ms ease-out;
        -webkit-transition: all 400ms ease-out;
    }
    .top_menu>li.out:nth-of-type(4) {
        -webkit-transform: translate(45px, 145px) rotate(0deg);
        transition: all 300ms ease-out;
        -webkit-transition: all 300ms ease-out;
    }
    .top_menu>li.out:nth-of-type(5) {
        -webkit-transform: translate(45px, 100px) rotate(0deg);
        transition: all 200ms ease-out;
        -webkit-transition: all 200ms ease-out;
    }
    .top_menu>li.out:nth-of-type(6) {
        -webkit-transform: translate(45px, 55px) rotate(0deg);
        transition: all 100ms ease-out;
        -webkit-transition: all 100ms ease-out;
    }
    .top_menu>li.out { width: 20px; height: 20px; min-width: 20px; border-radius: 20px; padding: 0; opacity: 0; }
    .top_menu>li.out a { display:none; }
    #sharemcover { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: none; z-index: 20000; }
    #sharemcover img { position: fixed; right: 18px; top: 5px; width: 260px; height: 180px; z-index: 20001; border:0; }
</style>
<div class="top_bar" style="-webkit-transform:translate3d(0,0,0)">
    <nav>
        <ul id="top_menu" class="top_menu">
            <input type="checkbox" id="plug-btn" class="plug-menu themeStyle" style="background-color:;background-image:url('/images/plug.png');border:0px;">
            <li class="themeStyle out" style="background:"> <a href="tel:88888888"><img src="/images/plugmenu1.png"><label>一键拨号</label></a></li>
            <li class="themeStyle out" style="background:"> <a href="javascript:void(0)"><img src="/images/plugmenu3.png"><label>一键导航</label></a></li>
            <li class="themeStyle out" style="background:"> <a href="javascript:void(0)"><img src="/images/plugmenu5.png"><label>一键分享</label></a></li>
            <li class="themeStyle out" style="background:"> <a href="javascript:void(0)"><img src="/images/plugmenu6.png"><label>微官网</label></a></li>
        </ul>
    </nav>
</div>


<div id="plug-wrap" style="display: none;" ></div>
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

<!--网页主体-->
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
                        <img src="/<?= $onecarousel['imgurl'] ?>" /></a><!--图片大小未统一-->
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

<ul class="mainmenu">
    <li><a href="/" ><b><img src="/images/tb01.png" /></b><span>新闻中心</span></a></li>
    <li><a href="/" ><b><img src="/images/tb02.png" /></b><span>关于我们</span></a></li>
    <li><a href="/" ><b><img src="/images/tb03.png" /></b><span>产品展示</span></a></li>
    <li><a href="/" ><b><img src="/images/tb08.png" /></b><span>成功案例</span></a></li>
    <li><a href="/" ><b><img src="/images/tb05.png" /></b><span>下载中心</span></a></li>
    <li><a href="/" ><b><img src="/images/tb06.png" /></b><span>团队介绍</span></a></li>
    <li><a href="/" ><b><img src="/images/tb06.png" /></b><span>人才招聘</span></a></li>
    <li><a href="/" ><b><img src="/images/tb07.png" /></b><span>联系我们</span></a></li>
    <li><a href="/" ><b><img src="/images/tb04.png" /></b><span>在线留言</span></a></li>
</ul>


<script type="text/javascript">
    var count = document.getElementById("thelist").getElementsByTagName("img").length;

    var count2 = document.getElementsByClassName("menuimg").length;
    for(i=0;i<count;i++){
        document.getElementById("thelist").getElementsByTagName("img").item(i).style.cssText = " width:"+document.body.clientWidth+"px";
    }
    document.getElementById("scroller").style.cssText = " width:"+document.body.clientWidth*count+"px";

    setInterval(function(){
        myScroll.scrollToPage('next', 0,400,count);
    },3500 );

    window.onresize = function(){
        for(i=0;i<count;i++){
            document.getElementById("thelist").getElementsByTagName("img").item(i).style.cssText = " width:"+document.body.clientWidth+"px";
        }
        document.getElementById("scroller").style.cssText = " width:"+document.body.clientWidth*count+"px";
    }
</script>

<div class="copyright"><br /><br />Copyright © 2014-2015 <a href="home/index">蓝标打印机<br /> All rights reserved.</div>

</body>
</html>