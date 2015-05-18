<?php
use yii\helpers\Url;
$this->title = '机器页面';
?>


<form method="post" id="wechat-form" action="<?=Url::toRoute(['m/machineposition','mid'=>$id])?>">

<div class="h-center-wrap">
    <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
    <input name="TblRentApply[latitude]" type="hidden" id="tbl_latitude"/>
    <input name="TblRentApply[longitude]" type="hidden" id="tbl_longitude"/>
    <input name="TblRentApply[accuracy]" type="hidden" id="tbl_accuracy"/>

    <button id="access-order" class="h-link" href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        录入机器坐标
    </button>
    <a class="h-link" href="<?=Url::toRoute(['rent/detail','id'=>$id,'from'=>'scan'])?>">
        机器信息
    </a>
</div>

</form>


<?php
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$wid,
    'apiList'=>['getLocation'],
    'jsReady'=>'
    document.querySelector("#access-order").onclick = function () {
        wx.getLocation({
            success: function (res) {
                document.getElementById("tbl_latitude").value = res.latitude;
                document.getElementById("tbl_longitude").value = res.longitude;
                document.getElementById("tbl_accuracy").value = res.accuracy;
                document.getElementById("wechat-form").submit();
            }
        });
        return false;
    };'
])
?>