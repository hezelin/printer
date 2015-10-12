<?php
$this->title = '公众号二维码';
?>

<h4>公众号二维码</h4>
<div class="alert alert-info" role="alert">
    1.如果公众号不能正确显示，是因为 添加公众号的时候  微信号填写错误，修正微信号即解决
</div>
<div class="col-md-12">
    <button id="print-btn" class="btn btn-info">打印公众号二维码</button>
</div>
<div class="col-md-4" id="print-wrap">
    <img src="http://open.weixin.qq.com/qr/code/?username=<?=$wx_num?>" />
</div>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        $('#print-btn').click(function(){
            $('#print-wrap').printArea();
        });
        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJsFile('/js/jquery.PrintArea.js',['depends'=>['yii\web\JqueryAsset']]);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>