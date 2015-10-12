<?php
    $this->title = '生成机器码';
?>

<style>
    .a4-wrap{width: 670px;}
    .a4{ width: 161px; height: 161px; position: relative; border:2px solid #fff; margin: 0; padding: 0;}
</style>
<div class="col-md-2 col-md-offset-1">
    <button class="btn btn-primary" id="print-btn"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;打印&nbsp;&nbsp;</button>
</div>
<div class="col-md-2" style="margin-left: 30px;">
    <select id="col-qrcode-num" class="form-control">
        <option value="4">每行4个二维码</option>
        <option value="5">每行5个二维码</option>
        <option value="6">每行6个二维码</option>
        <option value="7">每行7个二维码</option>
        <option value="8">每行8个二维码</option>
        <option value="9">每行9个二维码</option>
        <option value="10">每行10个二维码</option>
    </select>
</div>

<p>&nbsp;</p>

<div class="col-md-10 col-md-offset-1" style="margin-top: 20px;">
    <div  id="print-wrap" class="a4-wrap">
        <?php if(isset($imgUrl) && is_array($imgUrl)):?>
            <?php foreach($imgUrl as $src):?>
                    <img src="<?=$src?>" class="a4" />
            <?php endforeach;?>
        <?php endif;?>
    </div>
</div>


<script>
    var a4_width = 670;
    <?php $this->beginBlock('JS_END') ?>
        $('#print-btn').click(function(){
            $('#print-wrap').printArea();
        });

        $('#col-qrcode-num').change(function(){
            var wid = Math.floor( a4_width/parseInt( $(this).val() )) - 4;
            console.log(wid);
            $('.a4').css({'width':wid,'height':wid});
//            $('.a4').attr('width',wid);
            console.log('22222');
        });

    <?php $this->endBlock();?>
</script>
<?php
    $this->registerJsFile('/js/jquery.PrintArea.js',['depends'=>['yii\web\JqueryAsset']]);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>