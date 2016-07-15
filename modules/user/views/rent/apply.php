<?php

$this->title = '提交申请';
use app\assets\AuicssAsset;
AuicssAsset::register($this);

?>

<!-- aui dialog index start! -->
<div class="aui-dialog aui-hidden" id="dialog">
    <div class="aui-dialog-title" style="font-size:18px; font-weight:700; padding-top:15px;">提示</div>
    <div class="aui-dialog-header" style="padding:15px; padding-top:10px; font-weight:700; font-size: 16px;"></div>
    <div class="aui-dialog-footer aui-border-t">
        <div class="aui-dialog-btn aui-text-info delete_true" style="padding:10px 0; font-weight:700; font-size: 16px;" data-id="1">确定</div>
    </div>
</div>
<div class="aui-mask aui-hidden"></div>
<!-- aui dialog index ok! -->

<div class="aui-card" style="margin:10px 0; border-radius:0px;">
    <form method="post" id="wechat-submit">
        <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
        <div class="aui-form">
            <div class="aui-input-row">
                <span class="aui-input-addon">申请人姓名</span>
                <input type="text" id="apply-name" class="aui-input" name="TblRentApply[name]" placeholder="必填">
            </div>
            <div class="aui-input-row">
                <span class="aui-input-addon">手机</span>
                <input type="text" id="apply-phone" class="aui-input" name="TblRentApply[phone]" placeholder="必填">
            </div>
            <div class="aui-btn-row">
                <button type="submit" class="aui-btn aui-btn-block aui-btn-success" style="padding:6px 0; font-size: 14px;">提交租借申请</button>
            </div>
        </div>
    </form>
</div>


<script>
    <?php $this->beginBlock('JS_END') ?>
        ;!function(){
            var isMobile = /^(13|14|15|17|18)\d{9}$/;//手机号码验证规则
            var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
            with(document){
                var $btn = getElementById('wechat-submit');
                var $dialog =getElementById('dialog');
                var $bthTrue = getElementsByClassName('aui-mask')[0];
                var $phone = getElementById('apply-phone');
                var $name = getElementById('apply-name')
                var phoneVal = $phone.value.trim();
                var nameVal = $name.value.trim();
                $btn.addEventListener('submit', function(event){
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

                document.getElementsByClassName('delete_true')[0].addEventListener('touchend', function(){
                    $api.addCls($dialog, 'aui-hidden');
                    $api.addCls($bthTrue, 'aui-hidden');
                },false)

                function show(text){
                    $api.removeCls($dialog, 'aui-hidden');
                    $api.removeCls($bthTrue, 'aui-hidden');
                    $api.text(document.getElementsByClassName('aui-dialog-header')[0], text);
                }
            }
        }()

    <?php $this->endBlock();?>
</script>

<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
    $this->registerJsFile('/js/aui/api.js');
?>