<?php
    use yii\helpers\Url;
    $this->title = '维修进度';
?>
<style>
    .voice-row{ width: 100%; display: block;  float: left; margin:5px 0 10px;}
    #voice-wrap2{width: 60%; float: left; position: relative; border-radius: 30px; background-color: #CCFFFF;
        height: 40px; cursor: pointer;}
    .voice-time{text-align:left; padding-left:10px; font-size:20px;height:40px;line-height:40px;color:#505050;}
    .voice-image{ margin:4px 10px 0 5px;height:32px;width:32px;float:left; background: url(/images/voice.png) -120px 0 no-repeat;}
    .voice-start .voice-image{background-position: 0 0;}
    .voice-stop .voice-image{background-position: -40px 0;}
    .voice-playing .voice-image{background-position: -120px 0;}
    .voice-play .voice-image{background-position: -80px 0;}
</style>
<div class="h-box">
    <div class="h-title h-gray h-hr">维修进度</div>
    <ul class="h-process">
        <?php if($model['process']):?>
            <?php foreach($model['process'] as $k=>$p):?>
                <?php echo $k==0? '<li class="h-active">':'<li>';?>
                    <i class="h-icon-circle"></i>
                    <p class="h-text">
                        <?=$p['content']?>
                    </p>
                    <p class="h-text-2"><?=date('Y-m-d H:i',$p['add_time'])?></p>
                </li>
            <?php endforeach;?>
            <li>
        <?php else:?>
            <li class="h-active">
        <?php endif;?>
            <i class="h-icon-circle"></i>
            <p class="h-text">发起维修申请</p>
            <p class="h-text-2"><?=date('Y-m-d H:i',$model['add_time'])?></p>
        </li>
    </ul>

</div>
<div class="h-block-30">&nbsp;</div>
<?php

if($model['processImg']){
    $extra = 'document.querySelector(".process-img").onclick = function () {
            wx.previewImage({
           current: "'.$model['processImg'][0].'",
           urls: '.json_encode($model['processImg']).'
         });
      };';
}else
    $extra = '';

\app\components\WxjsapiWidget::widget([
    'wx_id'=>$model['wx_id'],
    'apiList'=>['previewImage'],
    'jsReady'=>$extra
]);

?>
<script>
    <?php $this->beginBlock('JS_END2') ?>
    var playtime2,myAudio2;
    function get_less_time2(){
        var time = parseInt($('#voice-time2').text())-1;
        $('#voice-wrap2').removeClass('voice-play').addClass('voice-stop');
        if(time<0)
            $('#voice-time2').text('00');
        else if(time<10)
            $('#voice-time2').text('0'+time);
        else
            $('#voice-time2').text(time);
    }


    function play_ended2(){
        clearInterval(playtime2);
        $('#voice-wrap2').attr('data-value',3).removeClass('voice-stop').addClass('voice-playing');
        $('#voice-time2').text( $('#voice-wrap2').attr('data-time'));
    }

    $(function(){
        //录音控制
        $('#voice-wrap2').click(function(){
            var obj = $(this);
            var value = $(obj).attr('data-value');

            //播放录音
            if(value==3){
                obj.attr('data-value',4);
                obj.removeClass('voice-stop').addClass('voice-playing');
                myAudio2 = document.getElementById('myaudio2');
                myAudio2.play();
                playtime2 = setInterval(get_less_time2,1000);
            }

            //暂停播放
            if(value ==4){
                obj.attr('data-value',3);
                obj.removeClass('voice-playing').addClass('voice-stop');
                myAudio2.pause();
                clearInterval(playtime2);
            }
        });
    });
<?php $this->endBlock();?>
</script>
<?php
    \app\assets\ZeptoAsset::register($this);
    $this->registerJs($this->blocks['JS_END2'],\yii\web\View::POS_END);
?>
