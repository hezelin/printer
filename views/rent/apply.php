<?php
    $this->title = '提交申请';
?>
<style>
    .wechat-form{ margin-top: 30px;}
    .wechat-input{ width: 80%; display: block; margin: 5% auto;
    height: 40px; line-height: 40px; padding: 0 5px; font-size: 16px;
    color: #999999; border: 1px solid #CCCCCC;}
    .error-list{
        color: red;
    }
</style>
<div class="error-list">
    <?php
        echo $error? :'';
    ?>
</div>
<div class="wechat-form">
    <form method="post">
        <input name="_csrf" type="hidden" value="<?=\Yii::$app->request->csrfToken?>"/>
        <input type="text" id="apply-name" class="wechat-input" name="TblRentApply[name]" placeholder="姓名">
        <input type="text" id="apply-phone" class="wechat-input" name="TblRentApply[phone]" placeholder="手机">
        <button type="submit" id="wechat-submit" class="button">提交申请</button>
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