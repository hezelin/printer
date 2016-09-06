<?php

$this->title = '提交申请';
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

<!-- aui dialog index start! -->
<div class="aui-dialog-2 aui-hidden" id="dialog">
    <div class="aui-dialog-box-2">
        <div class="aui-dialog-title-2"></div>
        <div class="aui-border-t">
            <a class="aui-dialog-btn-2 aui-block aui-pull-left aui-text-info aui-border-r delete_true">取消</a>
            <a class="aui-dialog-btn-2 aui-text-info delete_true">确定</a>
        </div>
    </div>
    <div class="aui-mask-2 aui-hidden"></div>
</div>
<!-- aui dialog index ok! -->

<form method="post" id="wechat-submit">
    <div class="aui-card" style="margin:10px 0 12px 0; border-radius:0px;">
        <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
        <div class="aui-form">
            <div class="aui-input-row aui-border-t" style="padding:0; margin-bottom:10px;">
                <span class="aui-input-addon aui-padded-0-10">您的姓名</span>
                <input type="text" id="apply-name" class="aui-input" name="TblRentApply[name]" placeholder="请填入您的姓名">
            </div>
            <div class="aui-input-row aui-border-t"  style="padding:0;">
                <span class="aui-input-addon aui-padded-0-10">手机号码</span>
                <input type="text" id="apply-phone" class="aui-input" name="TblRentApply[phone]" placeholder="请填入您的手机号码">
            </div>
        </div>
    </div>
    <div class="aui-clearfix aui-overflow-hidden" style="border-bottom:3px solid #B8D4F1;">
        <button type="submit" class="aui-btn aui-btn-block aui-btn-info" style="border-radius:0; padding:9px 0; font-size: 15px;">提交租借申请</button>
    </div>
</form>




<script>
    <?php $this->beginBlock('JS_END') ?>
        ;!function(){

            var isMobile = /^(13|14|15|17|18)\d{9}$/;//手机号码验证规则
            var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
            with(document){
                var $btn = getElementById('wechat-submit');
                var $dialog =getElementById('dialog');
                var $bthTrue = getElementsByClassName('aui-mask-2')[0];
                var $phone = getElementById('apply-phone');
                var $name = getElementById('apply-name');
                var $delete = getElementsByClassName('delete_true');
                $btn.addEventListener('submit', function(event){
                    var phoneVal = $phone.value.trim();
                    var nameVal = $name.value.trim();
                    if(!nameVal){
                        show('姓名不能为空');
                        event.preventDefault();
                        return false;
                    }
                    if(!phoneVal){
                        show('手机不能为空');
                        event.preventDefault();
                        return false;
                    }
                    if( !isMobile.test(phoneVal) && !isPhone.test(phoneVal)){
                        show('手机号码格式不对');
                        event.preventDefault();
                        return false;
                    }
                }, false);

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
            }

            var err = String(<?= $error ?>);
            if(err !== ''){
                $api.text(document.getElementsByClassName('aui-dialog-title-2')[0], err);
                $api.removeCls($dialog, 'aui-hidden');
                $api.removeCls($bthTrue, 'aui-hidden');
            }
        }()

    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    $this->registerJsFile('/js/aui/api.js');
?>