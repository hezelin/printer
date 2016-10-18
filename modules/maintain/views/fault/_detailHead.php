
    <style>
        body{background-color: #f1f1f1 !important;}
        #voice-wrap{width: 60%; float: left; position: relative; border-radius: 30px; background-color: #CCFFFF;
            height: 40px; cursor: pointer;}
        .voice-time{text-align:left; padding-left:10px; font-size:20px;height:40px;line-height:40px;color:#505050;}
        .voice-image{ margin:4px 10px 0 5px;height:32px;width:32px;float:left; background: url(/images/voice.png) -120px 0 no-repeat;}
        .voice-start .voice-image{background-position: 0 0;}
        .voice-stop .voice-image{background-position: -40px 0;}
        .voice-playing .voice-image{background-position: -120px 0;}
        .voice-play .voice-image{background-position: -80px 0;}
    </style>
    <div class="h-box-line">
        <div class="h-box-title">故障信息</div>
        <div class="h-box-content">

            <div class="h-box-row">
                <div class="h-box-label">故障图片</div>
                <div class="h-box-text" id="previewImage" >
                    <?=count($model['cover_images'])?>张
                    <div class="h-box-img"> <img src="<?=$model['fault_cover']?>" /></div>
                    <em class="h-icon-go"></em>
                </div>
            </div>

            <div class="h-box-row">
                <div class="h-box-label">故障类型</div>
                <div class="h-box-text"><?=\app\models\ConfigBase::getFaultStatus($model['fault_type'])?></div>
            </div>
            <?php if( isset($model['voice_url']) && $model['voice_url']):?>
                <div class="h-box-row">
                    <div class="h-box-label">用户录音</div>
                    <div class="h-box-text">
                        <div id="voice-wrap" data-value="3" data-time="<?=$model['voice_len']?>">
                            <div class="voice-image voice-playing"></div>
                            <p class="voice-time"><span id="voice-time"><?=$model['voice_len']?></span>＂</p>
                        </div>
                        <audio hidden="true" preload="auto" onended="play_ended()" id="myaudio">
                            <source src="<?=$model['voice_url']?>" type="audio/mpeg">
                            <source src="<?=substr($model['voice_url'],0,-3)?>amr" type="audio/mpeg">
                            "不支持播放录音"
                        </audio>
                    </div>
                </div>

                <?php $this->beginBlock('JS_END') ?>
                var playtime,myAudio;
                function get_less_time(){
                var time = parseInt($('#voice-time').text())-1;
                $('#voice-wrap').removeClass('voice-play').addClass('voice-stop');
                if(time<0)
                $('#voice-time').text('00');
                else if(time<10)
                $('#voice-time').text('0'+time);
                else
                $('#voice-time').text(time);
                }

                function play_ended(){
                clearInterval(playtime);
                $('#voice-wrap').attr('data-value',3).removeClass('voice-stop').addClass('voice-playing');
                $('#voice-time').text( $('#voice-wrap').attr('data-time'));
                }

                $(function(){
                //录音控制
                $('#voice-wrap').click(function(){
                var obj = $(this);
                var value = $(obj).attr('data-value');

                //播放录音
                if(value==3){
                obj.attr('data-value',4);
                obj.removeClass('voice-stop').addClass('voice-playing');
                myAudio = document.getElementById('myaudio');
                myAudio.play();
                playtime = setInterval(get_less_time,1000);
                }

                //暂停播放
                if(value ==4){
                obj.attr('data-value',3);
                obj.removeClass('voice-playing').addClass('voice-stop');
                myAudio.pause();
                clearInterval(playtime);
                }
                });
                });
                <?php $this->endBlock();?>
                <?php
                $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
                ?>

            <?php endif;?>

            <?php if( $model['desc'] ):?>
                <div class="h-box-row">
                    <div class="h-box-label">故障描述</div>
                    <div class="h-box-text"><?=$model['desc']?></div>
                </div>
            <?php endif;?>

            <?php if( $model['remark'] && isset($showRemark)):?>
                <div class="h-box-row">
                    <div class="h-box-label">客服留言</div>
                    <div class="h-box-text h-color-red"><?=$model['remark']?></div>
                </div>
            <?php endif;?>

        </div>
    </div>