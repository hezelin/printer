<?php
use yii\helpers\Url;
$this->title = '机器页面';
?>

<style>

</style>
<form method="post" id="wechat-form" action="<?=Url::toRoute(['/maintain/machine/position','mid'=>$id])?>">

<div class="h-center-wrap">
    <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
    <input name="TblRentApply[latitude]" type="hidden" id="tbl_latitude"/>
    <input name="TblRentApply[longitude]" type="hidden" id="tbl_longitude"/>
    <input name="TblRentApply[accuracy]" type="hidden" id="tbl_accuracy"/>

    <button type="button" id="access-order" class="a-no-link h-link-minor" href="<?=Url::toRoute(['m/apply','id'=>$id])?>">
        录入机器坐标
    </button>
    <a class="a-no-link h-link" href="<?=url::toRoute(['/user/rent/user-machine','id'=>$id,'from'=>'scan'])?>">
        机器信息
    </a>

    <a class="a-no-link h-link" href="<?=url::toRoute(['/maintain/charge/add','id'=>$wid,'machine_id'=>$id])?>">
        收租录入
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