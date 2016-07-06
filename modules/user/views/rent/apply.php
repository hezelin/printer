<?php
    $this->title = '提交申请';
?>

<div class="h-error-list">
    <?=$error?>
</div>
<div class="h-form-wrap">
    <form method="post">
        <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
        <input type="text" id="apply-name" class="h-input" name="TblRentApply[name]" placeholder="姓名">
        <input type="text" id="apply-phone" class="h-input" name="TblRentApply[phone]" placeholder="手机">
        <button type="submit" id="wechat-submit" class="h-button">提交申请</button>
    </form>
</div>

<script>
    <?php $this->beginBlock('JS_END') ?>
    $(function(){
        var isMobile=/^(?:13\d|15\d|18\d|17\d)\d{5}(\d{3}|\*{3})$/; //手机号码验证规则
        var isPhone=/^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;   //座机验证规则
        $('#wechat-submit').click(function(){
            var phone = $.trim($('#apply-phone').val());
            if( !$.trim($('#apply-name').val()) ){
                alert('姓名不能为空');
                $('#apply-name').focus();
                return false;
            }
            if( !phone )
            {
                alert('手机不能为空');
                $('#apply-phone').focus();
                return false;
            }
            if( !isMobile.test(phone) && !isPhone.test(phone)){
                alert("手机号码格式不对");
                $('#apply-phone').focus();
                return false;
            }
        })

    });
    <?php $this->endBlock();?>
</script>

<?php
//    依赖 zepto
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>