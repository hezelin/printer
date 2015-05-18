<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '申请维修';
?>
<?php
if( Yii::$app->session->hasFlash('error') )
    echo Html::tag('div',Yii::$app->session->getFlash('error'),['class'=>'h-error']);
?>
    <div class="h-center-wrap">
        <div class="h-form">
            <form method="post" id="wechat-form">
                <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
                <input type="hidden" id="service-imgid" class="h-input" name="TblMachineService[imgid]" />
                <input type="hidden" name="TblMachineService[from_openid]" value="<?=$openid?>" />
                <textarea id="service-desc" class="h-area h-m-b-2" placeholder="故障描述" name="TblMachineService[desc]"></textarea>
                <p>&nbsp;</p>
                <div class="h-row">
                    <div class="h-label">故障类型</div>
                    <?= Html::dropDownList('TblMachineService[type]','',\app\models\ConfigBase::$faultStatus,['class'=>'h-label-input'])?>
                </div>
                <div class="h-row">
                    <div class="h-label">故障图片</div>
                    <div id="upload-img" class="h-img-box">
                        +
                    </div>
                </div>

                <br/>
                <br/>
                <button type="button" id="wechat-submit" class="h-button">提交故障</button>
            </form>
        </div>
    </div>

<?php
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$id,
//    'debug'=>true,
    'apiList'=>['chooseImage','uploadImage'],
    'jsReady'=>'

    var images = {
        localId:"",
        serverId:""
    };

    document.querySelector("#upload-img").onclick = function () {
        wx.chooseImage({
            success: function (res) {
                images.localId = res.localIds[0];
                var tm = "<img src=\" "+ res.localIds[0]+ "\"/>";
                document.getElementById("upload-img").innerHTML = tm;
            }
        });
    };


    function upload() {
        wx.uploadImage({
        localId: images.localId,
        success: function (res) {
            images.serverId = res.serverId;
            document.getElementById("service-imgid").value = res.serverId;
            document.getElementById("wechat-form").submit();
        },
        fail: function (res) {
            alert(JSON.stringify(res));
        }
      });
    }

    document.getElementById("wechat-submit").onclick = function () {
        if( document.getElementById("service-desc").value == ""){
            alert("描述不能为空");
            return false;
        }
        if( images.localId ){
            upload();
        }else{
            document.getElementById("wechat-form").submit();
        }

    };
  '
])

?>