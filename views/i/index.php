<?php
use yii\helpers\Url;

$this->title = '个人中心';

?>
<p style="width: 100%; height: 15%; float:left;">&nbsp;</p>
<style>
    #top{ height: 240px; background-color: #4684CF; color: #FFFFFF; text-align: left;
        position: relative; margin-bottom: 15px;
    }
    .top-content{ height: 80px; padding-top: 72px; width: 70%; margin: 0 auto;}
    .cover{border: 1px solid #FFFFFF; border-radius: 4px; width: 80px; height: 80px;
        float: left; margin-right: 15px;
    }
    .top-nickname{ height: 40px; line-height: 40px; font-size:18px;}
    .top-from{height: 40px; line-height: 40px; font-size: 16px;}
</style>
<div id="top">
    <div class="top-content">
        <img class="cover" src="<?=substr($model['headimgurl'],0,-1)?>96" />
        <h2 class="top-nickname"><?=$model['nickname']?></h2>
        <span class="top-from"><?=$model['province'],$model['city']?></span>
    </div>

</div>
<!--首页菜单-->
<ul id="home-menu">
    <li>
        <a href="<?= Url::toRoute(['/shop/i/order','id'=>$id]) ?>" >
            <b class="color-1">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAA4UlEQVRYR+2W4RHCIAyFyQZuohvYbuQmupG6gW7iBpjnQQ6xV5r2OPAu/CwJ+frySEuu8aLG9Z0BmAKmgCjgvR/4Sl43XMs7EeEM1eoK4MDoFxX+d/CDFThp882EqQfQgrNWwiT+GVvAhkYr99jjZ+PcmVVMyAA3LnoMALNt7gdgg/Q/qasUaA7A1LtonAUwMNwLcWGC+iwHJoSpsYaJ8yR/rQdGBoDRAJAXX8DvJL8rALQgylZ6C4zdtAV5vMwB3piaA5JfZRT/5y0oaa7ZNwXCx+hj6NJfUhUTatplAKbAG+BscSE4Ib6xAAAAAElFTkSuQmCC"/>
            </b>
            <span>我的订单</span>
        </a>
    </li>
    <li><a href="<?= Url::toRoute(['/shop/cart/list','id'=>$id]) ?>" >
            <b class="color-2">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACNUlEQVRYR8VXi1ECMRD1KpAOhAqUCjw70ArEDrACzwosQaxAOxAqECvwrECo4HyPSZjcXnK75GYgM5mI7Nt9+0l2Kc5OvIpc+03TXAH74vCPRVGsc3TtCDhl54GCraYQmBLynw5zA/nlEAIEXwsFEyitU0qPQeAZBKpjEWA+R87YAucF9gabUeDp0+Rzzn9Rnjgu5n8n51arJly0RtD1IR3qFCGE5xDyhvZRECHX0t2qCWB9inneeaeoJEaAntXYLMp9FHIJCNwKxsuQffQaAlRB6MkJRmvBWoSQozNMKVensFMEwijUYD2RMbcQgMwMuFeHfYMefm6t5EMkovAA8CJEagTwPZ34wea5xR6Hufe6+ggQ+OcEO1EwEFDTSN29TzGM0Ot7R6ITBRlO/9nqvYXA2IWRsmuEcJoyKtJjJq42IxEFFlGtkGDq/IPzC9J0IrksBG6Bfrd4HpFRm5SFwBKKZaOy8IleOwnUijD0fgVwZbHMNPV10lCHRoD5Tr5iRjK9Yn3vQAmkHzhM4cwhZCWQozvEJItRS0FuAUrC2QR4p2fYLMYha46ijA6t6jUcYtWC1VLAkYvtlOcCm6NWOHp1bLg+wImKkaPX7CHJkV3rhr6dekPqbRBPN3Gt2VIyPvgWwBstak0k9IcXofux8iWUfcO+n4SjKQaO4b4UX06zilBMRZxqyr580qgjvsSf/pdWdKb0BNVbAIVjCHNzHugtQK/UFSIjtdEIqwQsV2mIzMkJ/AMnoxYwF+eHuQAAAABJRU5ErkJggg=="/>
            </b>
            <span>我的购物车</span></a></li>
    <li><a href="<?= Url::toRoute(['/shop/i/score','id'=>$id]) ?>" >
            <b class="color-3">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABbElEQVRYR+1W7U3DMBCtR2ADNqFswAbtBlUnADboCHSC0g3aSWAENgjvVWcUnDvXd6mU/oilyEnsl3v35Ze0mHikie0vZgJmBLque0F6NriWkqYT5veUEmdzeHEqAXzkAxZWhhWSeNPWIrgBAfHgIAa2mD/lfo35Ve6fy0hEcRoBhvgJ1xZGdn1PYYSek8QZazk1ly1YC+E0Ap0YfYCRn4LAI56/+A5r/7AgEMK5CIinF0MWgfK9YEzitRSoxZY9dRLIqTsCx+76G9eKkMA9QN8Z4SGAvfSc3cTvcAyK12rDzLhPduy9GtHaQbQU5uyIMePM71gHmFsLPCloYT0TuN8IKKrWklJtzwkvTRWNqGGUSFsbaqo28iBag7Gpoi41pOuRNqyp6F2KUUhWbynHrFoev4Oi6YVyoGq9HxIXrkkNpeyrqlYUL8Vs34KLqGHtp7Smom1tmJscHpVqWFW1KM59FEdPIQs3E5g8Ar9SBjYwgEz0ogAAAABJRU5ErkJggg==" />
            </b><span>我的积分</span></a></li>
</ul>
