<?php
use yii\helpers\Url;

$this->title = '赚取积分';

?>
<p style="width: 100%; height: 15%; float:left;">&nbsp;</p>
<style>
    #top{ height: 220px; background-color: #3399CC; color: #FFFFFF; text-align: left;
        position: relative; margin-bottom: 15px;
    }
    .score-title{ height: 20px; line-height: 20px; font-size: 14px; padding-left: 15px;}
    .top-content{ height: 200px; line-height:200px;font-size: 60px;  text-align: center; font-family: "arial", "微软雅黑";}
    #home-menu .color-10{ background-color:#3399CC; }
    #home-menu .color-11{ background-color:#339966;}
    #home-menu .color-12{ background-color:#CCCC33;}
</style>

<div id="top">
    <p class="score-title">我的积分</p>
    <h1 class="top-content"><?=$totalScore?></h1>
</div>

<!--首页菜单-->
<ul id="home-menu">
    <li>
        <a href="<?=Url::toRoute(['mp','id'=>$id])?>" >
            <b class="color-10">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAACCklEQVRIS72WjVHCQBCFSQXagViBdiBWoHaAFQgVSAdCBUIHWIFYgVCBsQKhgvi+uMscN4noccPO7CS5S/bt25/bFJ3MUlXVqUw+SS9NS13HRVFMgCpy4gmsK3uvUq6xzAV6lxtwIZQrQ4LRUnorvbG1YTZAY/dhhu/FZuoUtTc30GVOwJ6FsyOwHbsCHFhe8+VQRvsy+mysroVJeGvR3liXB+nmYIYyBrNHKVcXcjcENHJklgzYAvQpoLMAOLzd6KEbx5qwUFH0EoKnE3laBuFpYgTQiEKRI1TlVHoSoL3pfqD9n6KxZqV/aNYmIekraRy6LVD8kUWA5TJ02AFhcmEfYXhhLPE29NTttgK1OLxdLqKkkmgqqhZjDrg7kwzkNgF0di8Cg9GOGOiXLe44tI9N0z6A1T5jegWWHFkzOdVPAQoZ/geQiqWAkiUMaX2aN4S0qzU/I7OElBD5kUSv1HMrKJqwXUqt02+zVIpNbUERkTOanyLyQyDESAYOG58R4rMsJjDUAo6Mondaga3xqY8NJ4wbbDraetokbwgv8nuA4VrMUCuwHW2kKIzMQs/kP30e/gLszsZRWmvhPHla7GHM8UgB5htPsft/HMDrgxlGTGmho/1iMNrezYG2n6hVNoZWwVSjtxZTh+ruSX0o5PtNNEBYAto0Q+tplJWhgdJ/sPPhTcVOfc5+Az+fCMSHN6hSAAAAAElFTkSuQmCC" />
            </b>
            <span>分享公众号</span>
        </a>
    </li>
    <li><a href="<?=Url::toRoute(['rent','id'=>$id])?>" >
            <b class="color-11">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAABSUlEQVRIS+1WWxHCMBAkCsABOAAHFCdIKAqgCkACTqAOwAE4KArCbueuA31Akga+ejOdtsnlNtnsXWJGfzZTx7PWLtC2jzSPjTHm8hqrDTCBwykS4AqAZx/ALBB4K+P8ADG7BgMuE8C22AGwYgpsVKIZKO0S0FfRCI1LCTDDey3fOxdVtvjouCP6btKfMycNwNipeRMY33lYRsAC7mPnIX6OV7gz/gTPnN8E1CT1C+XmXVaaN+X/GLAxrV+vcAB0U0Ifr/oe3kXGlDAtlzdvAd9S5wEfPd21iDAtmBJTnWQdkAfuGU954mvxhpLZpkG6FshKkrDztbThl21VYXEC9KWwNyACHACqNHfhX7HCNMoK5SbHvfhkhd7Qeq8wNqVUltJ1FKWRQlopggCjyGikmApfy39O0bBBAdiulT0ApzFETwvtSP9+EX4Cb5vaQGxCucwAAAAASUVORK5CYII=" />
            </b>
            <span>邀请朋友租机</span></a></li>
    <li><a href="<?=Url::toRoute(['mall','id'=>$id])?>" >
            <b class="color-12">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAABJUlEQVRIS+VW7Q2CMBSkE+gIbqAbqJPoCDiBOIGOAJOIG+AGuIFOUO+w1AbTpkjpD32JQclx57v3UUQSOURkvSS+oJQyQ5bSkWkhhKiBWwGzHOqIAJFLjPxrCJbqj+1DCl5BdleEU1zn6ntX8IH7VU9hzWdm2BCTSNl3tghegKO93mHy/Zcg62LWcGGxlJhvatjwmZYW+F0rkRmuG4vgDfdz7wK+gJrvv2oYfSxsZRlt0+yM7mNHHS1Nw42UejTNCZh2W2n4aE2D7VJChcue3a+7OobgAaswa1M0BWlTO9C0lJYwujUkxtdS8lgFQ58WFJu4BIOOhVFDa4a/Lxj6tGANedJbLR1r03wIckBdkWKOKjTBFiB++kaO59+D3/fpofjoL8JP8z1jteAw6+UAAAAASUVORK5CYII=" />
            </b><span>分享办公耗材</span></a></li>
</ul>
