<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '故障确定';
?>
<?php
if( Yii::$app->session->hasFlash('error') )
    echo Html::tag('div',Yii::$app->session->getFlash('error'),['class'=>'h-error']);
?>
<style>
    .voice-row{ margin-bottom: 15px; position: relative;}
    #voice-wrap{width: 60%; float: left; position: relative; border-radius: 30px; background-color: #CCFFFF;
        height: 40px; cursor: pointer; display: none;}
    .voice-time{text-align:left; padding-left:10px; font-size:20px;height:40px;line-height:40px;color:#505050;}
    .voice-image{ margin:4px 10px 0 5px;height:32px;width:32px;float:left; background: url(/images/voice.png) 0 0 no-repeat;}
    .voice-start .voice-image{background-position: 0 0;}
    .voice-stop .voice-image{background-position: -40px 0;}
    .voice-playing .voice-image{background-position: -120px 0;}
    .voice-play .voice-image{background-position: -80px 0;}
    .voice-del-icon{height:32px; width: 32px; background: url(/images/voice.png) -160px 0 #ff9e9d no-repeat;
        margin: 4px 0 0 14px; border-radius: 30px;
    }
    #voice-del{
        position: absolute; width: 50px; height: 40px; right: 0; top: 0; display: none;
    }
</style>

    <div class="h-center-wrap">
        <div class="h-form">
            <form method="post" id="wechat-form">
                <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
                <input type="hidden" id="service-imgid" class="h-input" name="TblMachineService[imgid]" />
                <input type="hidden" id="service-voice" name="voice" />
                <input type="hidden" id="service-voice-len" name="voiceLen" value="0" />
                <input type="hidden" name="TblMachineService[from_openid]" value="<?=$openid?>" />
                <textarea id="service-desc" class="h-area h-m-b-2" placeholder="故障描述" name="TblMachineService[desc]"></textarea>
                <div class="h-title">故障图片</div>
                <div class="h-row-left">
                    <div id="img-show-wrap"></div>
                    <div id="upload-img" class="h-img-box" style="display: none;">
                        +
                    </div>
                </div>

                <div class="h-row-left voice-row">
                    <div class="h-label" id="voice-label">点击录音</div>
                    <div id="voice-wrap" data-value="1">
                        <div class="voice-image"></div>
                        <p class="voice-time"><span id="voice-time" data-time="0">00</span>＂</p>
                    </div>
                    <div id="voice-del">
                        <div class="voice-del-icon">&nbsp;</div>
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
    'apiList'=>['chooseImage','uploadImage','startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'stopVoice',
        'uploadVoice',
        'onVoiceRecordEnd',
        'onVoicePlayEnd'
    ],
    'jsReady'=>'

    wx.onVoicePlayEnd({
        success: function (res) {
            $("#voice-wrap").attr("data-value",3).removeClass("voice-playing").addClass("voice-play");
            $("#voice-label").text("点击播放");
            $("#voice-time").text( $("#voice-wrap").attr("data-time"));
            clearInterval(t);
        }
    });
    
    wx.onVoiceRecordEnd({
        complete: function (res) {
            voice_localId = res.localId;
            $("#voice-wrap").attr("data-value",3).removeClass("voice-stop").addClass("voice-play");
            $("#voice-wrap").attr("data-time",$("#voice-time").text());
            $("#voice-label").text("点击播放");
            $("#voice-del").show();
            clearInterval(t);
        }
    });

    document.getElementById("voice-wrap").style.cssText = "display: block";
    '
])

?>

<?php
\app\assets\FaultApplyAsset::register($this);
?>