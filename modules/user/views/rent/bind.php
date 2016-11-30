<?php

$this->title = '绑定资料';
use app\assets\AuicssAsset;
AuicssAsset::register($this);

?>
<style>
    body{
       background: #f8f8f8;
    }
    .aui-input-row .aui-padded-0-10{
        padding:0 10px; !important;
    }
</style>
<p class="aui-padded-10">
    申请维修之前，需要先绑定资料
</p>
<!-- aui dialog index start! -->
<div class="aui-dialog-2 aui-hidden" id="dialog">
    <div class="aui-dialog-box-2">
        <div class="aui-dialog-title-2"></div>
        <div class="aui-border-t">
            <a class="aui-dialog-btn-2 aui-block aui-pull-left aui-text-info delete_true aui-border-r delete_true">取消</a>
            <a class="aui-dialog-btn-2 aui-text-info delete_true">确定</a>
        </div>
    </div>
    <div class="aui-mask-2 aui-hidden"></div>
</div>
<!-- aui dialog index ok! -->

<form method="post" id="wechat-submit">
    <div class="aui-card" style="margin:10px 0 12px 0; border-radius:0px;">
        <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
        <input name="TblRentApply[latitude]" type="hidden" id="tbl_latitude"/>
        <input name="TblRentApply[longitude]" type="hidden" id="tbl_longitude"/>
        <input name="TblRentApply[accuracy]" type="hidden" id="tbl_accuracy"/>

        <div class="aui-form">
            <div class="aui-input-row aui-border-t" style="padding:0; margin-bottom:10px;">
                <span class="aui-input-addon aui-padded-0-10">您的姓名</span>
                <input type="text" id="apply-name" class="aui-input" name="TblRentApply[name]" placeholder="请填入您的姓名">
            </div>
            <div class="aui-input-row aui-border-t"  style="padding:0; margin-bottom:10px;">
                <span class="aui-input-addon aui-padded-0-10">手机号码</span>
                <input type="text" id="apply-phone" class="aui-input" name="TblRentApply[phone]" placeholder="请填入您的手机号码">
            </div>
            <div class="aui-input-row aui-border-t"  style="padding:0;">
                <span class="aui-input-addon aui-padded-0-10">您的地址</span>
                <input type="text" id="apply-address" class="aui-input" name="TblRentApply[address]" placeholder="越详细越好">
            </div>
        </div>
    </div>
    <div class="aui-clearfix aui-overflow-hidden" style="border-bottom:3px solid #B8D4F1;">
        <button id="bind-btn" type="button" class="aui-btn aui-btn-block aui-btn-info" style="border-radius:0; padding:9px 0; font-size: 15px;">加载中...</button>
    </div>
    <p>提示：如果一直显示加载中，请刷新或重新扫描</p>
</form>



<script>
    <?php $this->beginBlock('JS_END') ?>
    var isMobile = /^(13|14|15|17|18)\d{9}$/;//手机号码验证规则
    var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
    var $dialog = document.getElementById('dialog');
    var $bthTrue = document.getElementsByClassName('aui-mask-2')[0];
    var $delete = document.getElementsByClassName('delete_true');
    for(var i=0, len=$delete.length; i<len; i++ ){
        $delete[i].addEventListener('touchend', function(){
            $api.addCls($dialog, 'aui-hidden');
            $api.addCls($bthTrue, 'aui-hidden');
        },false)
    }
    function show(text){
        $api.removeCls($dialog, 'aui-hidden');
        $api.removeCls($bthTrue, 'aui-hidden');
        $api.text(document.getElementsByClassName('aui-dialog-title-2')[0], text);
    }

    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    $this->registerJsFile('/js/aui/api.js');
?>

<?php
\app\components\WxjsapiWidget::widget([
    'wx_id'=>$wx_id,
    'apiList'=>['getLocation'],
    'jsReady'=>'
    document.querySelector("#bind-btn").innerHTML = "绑定资料";

    document.querySelector("#bind-btn").onclick = function () {
        var phoneVal = document.getElementById("apply-phone").value.trim();
        var nameVal = document.getElementById("apply-name").value.trim();
        var addressVal = document.getElementById("apply-address").value.trim();
        if(!nameVal){
            show("姓名不能为空");
            return false;
        }
        if(!phoneVal){
            show("手机不能为空");
            return false;
        }
        if( !isMobile.test(phoneVal) && !isPhone.test(phoneVal)){
            show("手机号码格式不对");
            return false;
        }
        if(!addressVal){
            show("地址不能为空");
            return false;
        }

        wx.getLocation({
            success: function (res) {
                document.getElementById("tbl_latitude").value = res.latitude;
                document.getElementById("tbl_longitude").value = res.longitude;
                document.getElementById("tbl_accuracy").value = res.accuracy;
                document.getElementById("wechat-submit").submit();
            },
            fail: function(res){
                alert(res.errMsg);
            }
        });

        return false;
    };'
])
?>