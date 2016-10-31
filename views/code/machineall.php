<?php
$this->title = '批量打印二维码';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    #qrcode-series{position: absolute;top:65px;left:50px;font-size:4em;color:orangered;}
    #qrcode-img{position:absolute;top:22px;left:330px;width:120px;}
</style>
<div class="col-md-10 col-md-offset-1">
    <button class="btn btn-primary" id="print-btn"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;批量打印640px&nbsp;&nbsp;</button>
    <button class="btn btn-primary" style="margin-left: 15px;" id="print-btn-2"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;批量打印320px&nbsp;&nbsp;</button>
    <a href="<?=\yii\helpers\Url::toRoute(['setting','id'=>$model[0]['id']])?>" class="btn btn-default" style="margin-left: 15px;"><i class="glyphicon glyphicon-qrcode"></i> 二维码设置</a>
</div>

<div class="col-md-offset-1" style="width:1300px;">
    <div id="print-wrap">
        <?php foreach($model as $row):?>
        <div style="width:<?=$data['img']['bgWidth'] ?>px; float: left;">
            <div style="position: relative;<?=$data['img']['style']?>; overflow:hidden;">
                <img src="<?=$data['img']['img']?>" <?=$data['img']['width']?>/>
                <div id="user-id" style="position:absolute;<?=$data['apply']?>">
                    <?=$row['id']?>
                </div>
                <div id="qrcode-img" style="position:absolute;<?=$data['code']?>z-index:1000;">
                    <img src="<?=$row['qrcodeImgUrl']?>" width="100%"/>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>

    <div id="print-wrap-2" style="display: none;">
        <?php foreach($model as $row):?>
            <div style="width:320px; float: left;">
                <div style="position: relative;width:320px; overflow:hidden;">
                    <img src="<?=$data['img']['img']?>" width="320">
                    <div id="user-id" style="position:absolute;color:#777;font-size:16px;top:103px;left:223px;">
                        <?=$row['id']?>
                    </div>
                    <div id="qrcode-img" style="position:absolute;width:95px;top:8px;left:217px;z-index:1000;">
                        <img src="<?=$row['qrcodeImgUrl']?>" width="100%"/>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>


<script>
    <?php $this->beginBlock('JS_END') ?>

    $('#print-btn').click(function(){
        $('#print-wrap-2').hide();
        $('#print-wrap').show().printArea();
    });

    $('#print-btn-2').click(function(){
        $('#print-wrap').hide();
        $('#print-wrap-2').show().printArea();
    });

    <?php $this->endBlock();?>
</script>
<?php
    $this->registerJsFile('/js/jquery.PrintArea.js',['depends'=>['yii\web\JqueryAsset']]);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>