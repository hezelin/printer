<?php
$this->title = '微网站首页';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />

    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="Keywords" content="微盟、微信营销、微信代运营、微信定制开发、微信托管、微网站、微商城、微营销" />
    <meta name="Description" content="微盟，国内最大的微信公众智能服务平台，微盟八大微体系：微菜单、微官网、微会员、微活动、微商城、微推送、微服务、微统计，企业微营销必备。" />
    <!-- Mobile Devices Support @begin -->
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <meta content="telephone=no, address=no" name="format-detection">
    <meta name="apple-mobile-web-app-capable" content="yes" /> <!-- apple devices fullscreen -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <!-- Mobile Devices Support @end -->
    <link rel="shortcut icon" href="other" />
</head>

<body onselectstart="return true;" ondragstart="return false;" style="margin-top: 52px; width:100%">
<div data-role="container" class="">
    <header data-role="header">
        <div data-role="widget" data-widget="music105" class="music105">
            <link rel="stylesheet" href="other/music105.css">
            <script src="other/player.js" ></script>
            <a href="javascript:void(0);" class="btn_music" onclick="playbox.init(this).play();"></a><audio id="audio" loop src="http://video.weimob.com/video/66/5a/5e/mp3/20141127/雨的印记.mp3" style="pointer-events:none;display:none;width:0!important;height:0!important;"></audio>
        </div>
        <section id="imgSwipe" class="img_swipe">
            <ul>

                <?php foreach ($carousel as $onecarousel) {?>
                    <li>
                        <a href="<?php echo $onecarousel['link'];?>">
                            <div class="img"><img src="<?php echo $onecarousel['imgurl'];?>" /></div>
                            <div class="text">
                                <p style="margin-top: -10px"><?php echo $onecarousel['title'];?></p>
                            </div>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="swipe_num"><span id="curNum">1</span>/<span id="totalNum"></span></div>
        </section>
    </header>








    <section data-role="body">
        <div class="tel_wrap"><a href="tel:0662-3521188" class="ico_tel"></a></div>
        <div class="index_list" id="indexList">
            <ul>
                <li>
                    <a href="/weisite/channel?pid=366661&bid=220293&wechatid=fromUsername&categoryid=715253&wxref=mp.weixin.qq.com">
                        <i class="icon-home"></i>
                        <p>阳江资讯</p>
                    </a>
                </li>
                <li>
                    <a href="/weisite/channel?pid=366661&bid=220293&wechatid=fromUsername&categoryid=715230&wxref=mp.weixin.qq.com">
                        <i class="icon-search"></i>
                        <p>聚焦商机</p>
                    </a>
                </li>
                <li>
                    <a href="/weisite/channel?pid=366661&bid=220293&wechatid=fromUsername&categoryid=715685&wxref=mp.weixin.qq.com">
                        <i class="icon-unlink"></i>
                        <p>微城会友</p>
                    </a>
                </li>
                <li>
                    <a href="/weisite/channel?pid=366661&bid=220293&wechatid=fromUsername&categoryid=715252&wxref=mp.weixin.qq.com">
                        <i class="icon-android"></i>
                        <p>便民中心</p>
                    </a>
                </li>
                <li>
                    <a href="/webmidautumn/index?id=10044&pid=366661&wechatid=fromUsername&v=b4292b1ca7efa5acbdf31c0d71950d17&wxref=mp.weixin.qq.com">
                        <i class="icon-gift"></i>
                        <p>最新活动</p>
                    </a>
                </li>
                <li>
                    <a href="http://3g.inbai.com/interface/signin/signin.jsp?agentid=47610">
                        <i class="icon-wmfont wm-calculate"></i>
                        <p>签到送话费</p>
                    </a>
                </li>
                <li>
                    <a href="/userinfo/index?id=&pid=366661&bid=220293&wechatid=fromUsername&wxref=mp.weixin.qq.com">
                        <i class="icon-credit-card"></i>
                        <p>微城会员卡</p>
                    </a>
                </li>
                <li>
                    <a href="/weisite/list?pid=366661&bid=220293&wechatid=fromUsername&ltid=984889&wxref=mp.weixin.qq.com">
                        <i class="icon-wrench"></i>
                        <p>本地服务</p>
                    </a>
                </li>
                <li>
                    <a href="./index.php?r=user/login">
                        <i class="icon-home"></i>
                        <p>我要登录</p>
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <footer data-role="footer">
        <div class="copyright" class="copyright" ><a href="/weisite/home?pid=366661&bid=220293&wechatid=fromUsername&wxref=mp.weixin.qq.com" style="color:#D42620">© 技术支持：XX</a></div>
    </footer>

    <div mark="stat_code" style="width:0px; height:0px; display:none;">
    </div>
</body>
<script type="text/javascript">
    (function() {
        var wtj = document.createElement('script'); wtj.type = 'text/javascript'; wtj.async = true;
        wtj.src = 'http://tj.weimob.com/wtj.js?url=' + encodeURIComponent(location.href);
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wtj, s);
    })();
    function weimobAfterShare(shareFromWechatId,sendFriendLink,shareToPlatform){
        var wmShare = document.createElement('script'); wmShare.type = 'text/javascript'; wmShare.async = true;
        wmShare.src = 'http://tj.weimob.com/api-share.js?fromWechatId=' + shareFromWechatId + '&shareToPlatform=';
        wmShare.src += shareToPlatform + '&pid=366661&sendFriendLink=' + encodeURIComponent(sendFriendLink);
        var stj = document.getElementsByTagName('script')[0]; stj.parentNode.insertBefore(wmShare, stj);
    }

</script>
<script type="text/javascript" src="other/ChatFloat.js"></script>
<script type="text/javascript">
    var str_domain = location.href.split('/',4)[2];
    var boolIsTest = true;
    if(str_domain == 'www.weimob.com' || str_domain.indexOf('m.weimob.com') > 0){
        boolIsTest = false;
    }
    //1.0 web
    new ChatFloat({
        AId: '366661',
        openid: "",
        top:150,
        right:0,
        IsTest:boolIsTest
    });
</script>
<!--
echo STATIC_DOMAIN."/src/jQuery.js?v=10101011
-->
<script type="text/javascript" src="other/share_channel.js"></script>
<script type="text/javascript" src="other/base64.js"></script>
<script type="text/javascript" src="http://stc.weimob.com/src/st/st.js?v=1427104540"></script>
<script type="text/javascript">
    st.push("_triggerEvent",{
        "is_statistic_on":"on", //开关
        "statisticServerPath": "http://statistic.weimob.com/wm.js", //统计地址
        "memcServerPath": "http://statistic.weimob.com/memc?cmd=get", //缓存地址
        "stat_action":"loadPage",  //统计动作类型
        "stat_optValue":""    //参数值
    });
</script>

</html>

