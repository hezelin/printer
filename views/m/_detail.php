<?php
use yii\helpers\Url;
/*
 * 故障详情 头部
 */
?>
<div id="fault-detail">
    <div class="aui-padded-10 aui-bg-default"><p class="aui-border-left aui-p-l">故障信息</p></div>
    <div class="aui-content">
        <ul class="aui-list-view aui-in">
            <li class="aui-list-view-cell">
                <a class="aui-arrow-right" id="previewImage">
                    <div class="aui-col-xs-3 aui-text-default">故障图片</div>
                    <div class="aui-col-xs-9">
                        <?=count($model['cover_images'])?>张
                        <div class="list-box-img">
                            <img src="<?=$model['fault_cover']?>" />
                        </div>
                    </div>
                </a>
            </li>

            <li class="aui-list-view-cell">
                <a class="aui-arrow-right" href="<?=Url::toRoute(['/s/detail2','id'=>$model['id']])?>">
                    <div class="aui-col-xs-3 aui-text-default">故障类型</div>
                    <div class="aui-col-xs-9">
                        <?=\app\models\ConfigBase::getFaultStatus($model['fault_type'])?>
                        <div class="list-box-img aui-text-primary">查看进度</div>
                    </div>
                </a>
            </li>

            <?php if( isset($model['voice_url']) && $model['voice_url']):?>
                <li class="aui-list-view-cell">
                    <div class="aui-col-xs-3 aui-text-default">用户录音</div>
                    <div class="aui-col-xs-9">
                        <div id="voice-wrap" data-value="3" data-time="<?=$model['voice_len']?>">
                            <span class="voice-image iconfont icon-shengyin"></span>
                            <span id="voice-time"><?=$model['voice_len']?></span>＂
                        </div>
                        <audio hidden="true" preload="auto" onended="play_ended()" id="myaudio">
                            <source src="<?=$model['voice_url']?>" type="audio/mpeg">
                            <source src="<?=substr($model['voice_url'],0,-3)?>amr" type="audio/mpeg">
                            "不支持播放录音"
                        </audio>
                    </div>
                    </div>
                </li>
            <?php endif;?>

            <?php if( $model['desc'] ):?>
            <li class="aui-list-view-cell">
                <div class="aui-col-xs-3 aui-text-default">故障描述</div>
                <div class="aui-col-xs-9">
                    <?=$model['desc']?>
                </div>
            </li>
            <?php endif;?>

            <?php if( $model['remark'] ):?>
            <li class="aui-list-view-cell">
                <div class="aui-col-xs-3 aui-text-default">客服留言</div>
                <div class="aui-col-xs-9 aui-text-danger">
                    <?=$model['remark']?>
                </div>
            </li>
            <?php endif;?>
        </ul>
    </div>
    <div class="aui-padded-10 aui-bg-default"><p class="aui-border-left aui-p-l">客户资料</p></div>
    <div class="aui-content">
        <ul class="aui-list-view aui-in">
            <li class="aui-list-view-cell">
                <div class="aui-col-xs-3 aui-text-default">客户信息</div>
                <div class="aui-col-xs-9">
                    <?= $model['name'],',<a class="h-tel" href="tel:',$model['phone'],'">',$model['phone']?></a>
                </div>
            </li>
            <li class="aui-list-view-cell">
                <div class="aui-col-xs-3 aui-text-default">客户地址</div>
                <div class="aui-col-xs-9"> <?= $model['address']?> </div>
            </li>
            <li class="aui-list-view-cell">
                <div class="aui-col-xs-3 aui-text-default">申请时间</div>
                <div class="aui-col-xs-9"><?= date('Y年m月d H:i',$model['add_time'])?></div>
            </li>

            <?php if($model['fault_score']):?>
            <li class="aui-list-view-cell">
                <div class="aui-col-xs-3 aui-text-default">用户评价</div>
                <div class="aui-col-xs-9"><?=$model['fault_score']?> 分</div>
            </li>
            <?php endif;?>

            <li class="aui-list-view-cell">
                <a class="aui-arrow-right" id="map-btn">
                    <div class="aui-col-xs-3 aui-text-default">地址坐标</div>
                    <div class="aui-col-xs-9 aui-text-primary">点击导航</div>
                </a>
            </li>

            <li class="aui-list-view-cell">
                <a class="aui-arrow-right" href="<?=Url::toRoute(['s/irecord','id'=>$model['wx_id'],'mid'=>$model['mid']])?>">
                    <div class="aui-col-xs-3 aui-text-default">历史维修</div>
                    <div class="aui-col-xs-9 aui-text-primary">
                        <?=($model['fault_num']+1)?>次
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
<?php $this->beginBlock('JS_END') ?>

var playtime,myAudio;
function get_less_time(){
    var time = parseInt($('#voice-time').text())-1;
    $('#voice-wrap').children('.voice-image').removeClass('icon-shengyin').addClass('icon-zanting');
    if(time<0)
        $('#voice-time').text('00');
    else if(time<10)
        $('#voice-time').text('0'+time);
    else
        $('#voice-time').text(time);
}


function play_ended(){
    clearInterval(playtime);
    $('#voice-wrap').attr('data-value',3);
    $('#voice-wrap').children('.voice-image').removeClass('icon-zanting').addClass('icon-shengyin');
    $('#voice-time').text( $('#voice-wrap').attr('data-time'));
}

$(function(){
    //录音控制
    $('#voice-wrap').click(function(){
        var obj = $(this);
        var value = $(obj).attr('data-value');
        var voiceImage = $(this).children('.voice-image')

        //播放录音
        if(value==3){
            obj.attr('data-value',4);
            voiceImage.removeClass('icon-shengyin').addClass('icon-zanting');
            myAudio = document.getElementById('myaudio');
            myAudio.play();
            playtime = setInterval(get_less_time,1000);
        }

        //暂停播放
        if(value ==4){
            obj.attr('data-value',3);
            voiceImage.removeClass('icon-zanting').addClass('icon-shengyin');
            myAudio.pause();
            clearInterval(playtime);
        }
    });
});
<?php $this->endBlock();?>
</script>
<?php
    \app\assets\ZeptoAsset::register($this);
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>