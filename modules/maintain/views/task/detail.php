<?php
use yii\helpers\Url;
$this->title = '维修任务';
/*
 * 确认接单页面
 */
?>
<?= $this->render('_detail', ['model' => $model ]) ?>

<form method="post" id="wechat-form" action="<?=Url::toRoute(['/maintain/task/process','id'=>$model['id'],'openid'=>$openid])?>">
    <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
    <input name="TblServiceProcess[status]" type="hidden" value="3"/>
    <input name="TblServiceProcess[latitude]" type="hidden" id="tbl_latitude"/>
    <input name="from" type="hidden" value="<?=$from?>"/>
    <input name="TblServiceProcess[longitude]" type="hidden" id="tbl_longitude"/>
<div id="access-order" class="aui-fixed-bottom">
    加载中...
</div>
</form>


<?php
$map = '';
if($model["latitude"])
    $map = <<< MAP
document.querySelector("#map-btn").onclick = function () {
            wx.openLocation({
                latitude: {$model["latitude"]},
                longitude: {$model["longitude"]},
                name: "维修地点",
                address: "'{$model["address"]}'",
                scale: 16,
                infoUrl: ""
            });
        };
MAP;

\app\components\WxjsapiWidget::widget([
    'wx_id'=>$model['wx_id'],
    'apiList'=>['getLocation','openLocation','previewImage'],
    'jsReady'=>'
     document.querySelector("#access-order").innerHTML = "确认接单";
    document.querySelector("#access-order").onclick = function () {
        wx.getLocation({
            type: "gcj02",
            success: function (res) {
                document.getElementById("tbl_latitude").value = res.latitude;
                document.getElementById("tbl_longitude").value = res.longitude;
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

      '.$map
]);

?>