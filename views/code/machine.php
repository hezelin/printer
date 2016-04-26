<?php
    $this->title = '生成机器码';
?>

<style>
    #qrcode-series{position: absolute;
        top: 138px;
        left: 335px;
        font-size: 1.5em;
        color: #444444;
    }
    #qrcode-user{position: absolute;
        top: 135px;
        left: 50px;
        font-size: 1.5em;
        color:#ff4500;
    }
    #user-id{position: absolute;top:65px;left:45px;font-size:4em;color:orangered;}
    #qrcode-img{position:absolute;top:22px;left:330px;width:120px;}
</style>
<div class="col-md-10 col-md-offset-1">
    <button class="btn btn-primary" id="print-btn"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;打印&nbsp;&nbsp;</button>
    <a href="<?=\yii\helpers\Url::toRoute(['setting','id'=>$data['machineId']])?>" class="btn btn-default" style="margin-left: 15px;"><i class="glyphicon glyphicon-qrcode"></i> 二维码设置</a>
</div>

<div class="col-md-10 col-md-offset-1">
    <div  id="print-wrap" style="position: relative;<?=$data['img']['style']?>">
        <img src="<?=$data['img']['img']?>" <?=$data['img']['width']?>/>
        <div id="user-id" style="position:absolute;<?=$data['apply']?>">
            <?=$data['applyId']? $data['come_from'].$data['applyId']:''?>
        </div>
        <div id="qrcode-user" style="position: absolute;<?=$data['user']?>">
            <?= $data['userName'] ?>
        </div>
        <div id="qrcode-img" style="position:absolute;<?=$data['code']?>">
            <img src="<?=$data['qrcodeImgUrl']?>" width="100%"/>
        </div>
        <div id="qrcode-series" style="position: absolute;<?=$data['series']?>">
            <?= $data['seriesNum'] ?>
        </div>
    </div>
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