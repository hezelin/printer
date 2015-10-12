<?php
use yii\helpers\Url;

$this->title = '分享办公耗材';

?>
<style>
    .share-text, .share-text li{list-style: none; margin: 0; padding: 0;}
    .share-title{ height: 50px; line-height: 50px; padding-left: 15px; font-size: 16px;}
    .share-text {
        border: 1px dotted #8f85ed;
        background-color: #d2d9ee;
        padding: 15px;
        line-height: 24px;
        margin: 0 15px;
        border-radius: 4px;
    }
    #shade-tips{width: 100%; height: 100%; display: none;
        position: fixed;top: 0; left: 0; text-align: right;
        color: #ffffff;}
    #shade-tips .shade-bg{ position:absolute; background-color: #000000; opacity: 0.8; width: 100%; height: 100%;}
    #shade-tips .shade-text{ position:absolute; width: 100%; height: 100%; font-size: 24px;}
    #shade-tips .shade-text p{ margin: 0 10%; width: 80%; display:block; text-align:center; font-size: 24px;}


</style>
    <div class="share-title">分享提示</div>
    <ul class="share-text">
        <li>1、送货上门就是方便。</li>
        <li>2、与你的朋友分享办公耗材，每天赚取100积分。</li>
        <li>3、你朋友通过你分享的链接成功购买，你将获得等金额积分</li>
        <li>4、积分可以减免现金购买耗材。</li>
        <li>5、积分还可以兑换神秘大礼包哦</li>
    </ul>

<div id="share-btn" class="h-button" style="width: 90%;">加载中...</div>

<div id="shade-tips">
    <div class="shade-bg">&nbsp;</div>
    <div class="shade-text">
        <img width="80px" src="/images/share-point.png" />
        <p>请点击右上角，将它发送给指定朋友或分享到朋友圈！</p>
    </div>
</div>

    <script>
        function syncShare(data)
        {
            $.post(
                '<?=Url::toRoute(['link','id'=>$id])?>',
                {origin:'<?=$from?>',type:data},
                function(resp){
//                alert(resp);
                }
            );
        }

    </script>

<?php
\app\assets\ZeptoAsset::register($this);
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$id,
    'apiList'=>['onMenuShareTimeline','onMenuShareAppMessage'],
    'jsReady'=>'
        wx.onMenuShareTimeline({
            title: "“'.$data['name'].'”,让你节省40%办公设备成本，省心省力！",
            link: "'.Url::toRoute(['/shop/item/list','id'=>$id,'origin'=>$from],'http').'",
            imgUrl: "'.Yii::$app->request->hostInfo.'/images/share-image.jpg",
            success: function () {
                syncShare(5);
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareAppMessage({
            title:  "'.$data['name'].'",
            desc: "让你节省40%办公设备成本，省心省力！",
            link: "'.Url::toRoute(['/shop/item/list','id'=>$id,'origin'=>$from],'http').'",
            imgUrl: "'.Yii::$app->request->hostInfo.'/images/share-image.jpg",
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        document.getElementById("share-btn").innerHTML = "分享办公耗材";
        document.getElementById("share-btn").onclick = function () {
            document.getElementById("shade-tips").style.cssText = "display:block";
        }

        document.getElementById("shade-tips").onclick = function () {
            document.getElementById("shade-tips").style.cssText = "display:none";
        }
      '
]);
?>