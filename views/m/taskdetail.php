<?php
use yii\helpers\Url;
$this->title = '维修任务';

?>
<style>
    body{ background-color: #ffffff !important;}
</style>

<div class="h-box">
    <div class="h-title h-gray h-hr">故障信息</div>
    <div class="h-box-row h-hr">
        <div class="h-left">
            <img id="previewImage" class="h-img" src="<?=$model['fault_cover']?>" />
        </div>
        <div class="h-right">
            <h4 class="h-row-1 h-hr">故障类型：<?=\app\models\ConfigBase::getFaultStatus($model['fault_type'])?></h4>
            <p class="h-row-2"><?=$model['desc']?></p>
        </div>
    </div>
</div>

<div class="h-box">
    <div class="h-title h-gray h-hr">客户资料</div>
    <div class="h-row">
        <div class="h-left-text">客户信息</div>
        <div class="h-right-text"><?= $model['name'],',<a class="h-tel" href="tel:',$model['phone'],'">',$model['phone']?></a></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">客户地址</div>
        <div class="h-right-text"><?= $model['address']?></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">申请时间</div>
        <div class="h-right-text"><?= date('Y年m月d H:i',$model['add_time'])?></div>
    </div>
    <div class="h-row">
        <div class="h-left-text">地址坐标</div>
        <div class="h-right-text" id="map-btn" style="color: #5c72ff; font-weight: 600;">点击导航</div>
    </div>
    <div class="h-row">
        <div class="h-left-text">历史维修</div>
        <a href="<?=Url::toRoute(['s/irecord','id'=>$model['wx_id'],'mid'=>$model['mid']])?>" class="h-right-text" style="color: #5c72ff; font-weight: 600;">点击查看</a>
    </div>

</div>
<form method="post" id="wechat-form" action="<?=Url::toRoute(['m/process','id'=>$model['id'],'openid'=>$openid])?>">
    <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
    <input name="TblServiceProcess[status]" type="hidden" value="3"/>
    <input name="TblServiceProcess[latitude]" type="hidden" id="tbl_latitude"/>
    <input name="from" type="hidden" value="<?=$from?>"/>
    <input name="TblServiceProcess[longitude]" type="hidden" id="tbl_longitude"/>
    <input name="TblServiceProcess[accuracy]" type="hidden" id="tbl_accuracy"/>

<button type="button" id="access-order" class="h-fixed-bottom">
    确认接单
</button>
</form>

<?php
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$model['wx_id'],
    'apiList'=>['getLocation','openLocation','previewImage'],
    'jsReady'=>'
    document.querySelector("#access-order").onclick = function () {
        wx.getLocation({
            success: function (res) {
                document.getElementById("tbl_latitude").value = res.latitude;
                document.getElementById("tbl_longitude").value = res.longitude;
                document.getElementById("tbl_accuracy").value = res.accuracy;
                if( !res.latitude ) {
                alert("获取位置失败");
                return false;
                }
                document.getElementById("wechat-form").submit();
            }
        });
        return false;
    };

    document.querySelector("#previewImage").onclick = function () {
            wx.previewImage({
              current: "'.$model["fault_cover"].'",
              urls: '.json_encode($model['cover_images']).'
            });
      };

    document.querySelector("#map-btn").onclick = function () {
            wx.openLocation({
                latitude: '.$model["latitude"].',
                longitude: '.$model["longitude"].',
                name: "维修地点",
                address: "'.$model["address"].'",
                scale: 16,
                infoUrl: ""
            });
        };'
])

?>