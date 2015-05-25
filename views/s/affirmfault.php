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
                <div class="h-title">故障图片</div>
                <div class="h-row-left">
                    <div id="img-show-wrap"></div>
                    <div id="upload-img" class="h-img-box">
                        +
                    </div>
                </div>
                <div class="h-row-left">
                    <div class="h-label">故障类型</div>
                    <?= Html::dropDownList('TblMachineService[type]','',\app\models\ConfigBase::$faultStatus,['class'=>'h-label-input'])?>
                </div>
                <p style="float: left; height: 30px;">&nbsp;</p>

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
        localId:[],
        serverId:[]
    };

    document.querySelector("#upload-img").onclick = function () {
        wx.chooseImage({
            success: function (res) {
                images.localId = res.localIds;
                var imgs = "";
                for(var i in res.localIds)
                imgs += "<div class=\"h-img-box\"><img src=\" "+ res.localIds[i]+ "\"/></div>";
                document.getElementById("img-show-wrap").innerHTML = imgs;
            }
        });
    };

    document.getElementById("wechat-submit").onclick = function () {
        if( document.getElementById("service-desc").value == ""){
            alert("描述不能为空");
            return false;
        }
        var i = 0, length = images.localId.length;

        function upload() {
            wx.uploadImage({
            localId: images.localId[i],
            success: function (res) {
                i++;
                images.serverId.push(res.serverId);
                if(i<length)
                    upload();
                if(i >= length){
                    document.getElementById("service-imgid").value=images.serverId.join("|");
                    document.getElementById("wechat-form").submit();
                }
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
          });
        }

        if( images.localId.length > 0 ){
            upload();
        }else{
            document.getElementById("wechat-form").submit();
        }

    };
  '
])

?>