<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '维修进度';
$this->params['breadcrumbs'][] = ['label'=>'维修资料','url'=>['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.process-img img{ width: 60px;}
.h-box-text{ width: 100%; margin: 8px 0; float: left;}
.voice-wrap{width: 200px; float: left; position: relative; border-radius: 32px; background-color: #CCFFFF;
    height: 32px; cursor: pointer;}
.voice-time{text-align:left; padding-left:10px; font-size:20px;height:32px;line-height:32px;color:#505050;}
.voice-image{ margin:0 10px 0 5px;height:32px;width:32px;float:left; background: url(/images/voice.png) -120px 0 no-repeat;}
.voice-start .voice-image{background-position: 0 0;}
.voice-stop .voice-image{background-position: -40px 0;}
.voice-playing .voice-image{background-position: -120px 0;}
.voice-play .voice-image{background-position: -80px 0;}
</style>
<?php
function getMachine($model){
    if( !isset($model->machine) ) return '<span class="not-set">（无设置）</span>';
    return Html::a(Html::img($model->machine->cover,['width'=>40]),str_replace('/s/','/m/',$model->machine->cover),['class'=>'fancybox','rel'=>'group']) .
            ' , ' . $model->machine->brand_name .
            ' , ' . $model->machine->model_name;
}

function getProcess($model,$process)
{
    $html = '<ul class="list-group">';
    foreach ($process as $k => $p) {
        $row = '';
        $row .= '<i class="h-icon-circle"></i><p class="h-text">';

        if($p['process'] == 5){
            $content = json_decode($p['content'],true);
            $row .= '故障：'.$content['text'];
            $row .= '<span class="small">&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i', $p['add_time']) . '</span></p>';
            if( isset($content['cover']) && is_array($content['cover']) ){
                $row .= '<div class="process-img">';
                foreach($content['cover'] as $img)
                    $row .= Html::a(Html::img($img,['width'=>50]),$img,['class'=>'fancybox','rel'=>'group']);
                $row .= '</div>';
            }

            if( isset($content['voice'],$content['voiceLen']) && $content['voice']){
                $row .= '<div class="h-box-text">
                    <div class="voice-wrap" data-value="3" data-time="'.$content['voiceLen'].'" data-id="2">
                        <div class="voice-image voice-playing"></div>
                        <p class="voice-time"><span class="voice-second">'.$content['voiceLen'].'</span>＂</p>
                    </div>
                    <audio hidden="true" preload="auto" onended="play_ended()" id="myaudio2">
                        <source src="'.$content['voice'].'" type="audio/mpeg">
                        "不支持播放录音"
                    </audio>
                </div>';
            }


        }else{
            $row .= $p['content'];
            $row .= '<span class="small">&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i', $p['add_time']) . '</span></p>';
        }

        $html .= Html::tag('li', $row);
    }

    $html .= Html::tag('li',
            '<i class="h-icon-circle"></i><p class="h-text"><p class="h-text"> 发起维修申请
            <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i',$model->add_time).'</span></p>'
        );
    return $html.'</ul>';
}

function getImage($content){
    $html = [];
    $content = json_decode($content,true);
    foreach( $content['cover'] as $cover)
        $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class'=>'fancybox','rel'=>'group']);
    return join("\n",$html);
}

function getVoice($content){
    $contents = json_decode($content,true);
    if( ! isset($contents['voice']) ) return '<span class="not-set">（无设置）</span>';

    return '<div class="h-box-text">
            <div class="voice-wrap" data-value="3" data-time="'.$contents['voiceLen'].'" data-id="1">
                <div class="voice-image voice-playing"></div>
                <p class="voice-time"><span class="voice-second">'.$contents['voiceLen'].'</span>＂</p>
            </div>
            <audio hidden="true" preload="auto" onended="play_ended()" id="myaudio1">
                <source src="'.$contents['voice'].'" type="audio/mpeg">
                "不支持播放录音"
            </audio>
        </div>';
}

?>


<table class="table table-striped detail-view">
<tbody>
<tr><th>故障图片</th><td><?=getImage($model->content)?></td></tr>
<tr><th>故障录音</th><td><?=getVoice($model->content)?></td></tr>
<tr><th>故障类型</th><td><?=ConfigBase::getFaultStatus($model->type)?></td></tr>
<tr><th>机器信息</th><td><?=getMachine($model)?></td></tr>
<tr><th>维修进度</th><td><?=getProcess($model,$process)?></td></tr>

</tbody>
</table>
<?php

// fancybox 预览图片

echo newerton\fancybox\FancyBox::widget([
    'target' => '.fancybox',
    'mouse' => true,
    'config' => [
        'maxWidth' => '100%',
        'maxHeight' => '100%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => true,
        'openOpacity' => true,
    ]
]);


?>

    <script>
        var playtime,myAudio,voiceWrap;
        function get_less_time(){
            var second = voiceWrap.find('.voice-second')
            var time = parseInt( second.text())-1;
            voiceWrap.removeClass('voice-play').addClass('voice-stop');
            if(time<0)
                second.text('00');
            else if(time<10)
                second.text('0'+time);
            else
                second.text(time);
        }

        function play_ended(){
            clearInterval(playtime);
            voiceWrap.attr('data-value',3).removeClass('voice-stop').addClass('voice-playing');
            voiceWrap.find('.voice-second').text( voiceWrap.attr('data-time'));
        }

        <?php $this->beginBlock('JS_END') ?>
        //录音控制
        $('.voice-wrap').click(function(){
            var oldVoice = voiceWrap;
            voiceWrap = $(this);
            var value = voiceWrap.attr('data-value');
            var id = voiceWrap.attr('data-id');
            //播放录音
            if(value==3){
                voiceWrap.attr('data-value',4);
                voiceWrap.removeClass('voice-stop').addClass('voice-playing');

                if( myAudio && oldVoice.attr('data-id') != voiceWrap.attr('data-id') ){
                    oldVoice.attr('data-value',3);
                    if( parseInt(oldVoice.attr('data-time')) > parseInt(oldVoice.find('.voice-second').text()) )
                        oldVoice.removeClass('voice-playing').addClass('voice-stop');
                    myAudio.pause();
                    clearInterval(playtime);
                }

                myAudio = document.getElementById('myaudio'+id);
                myAudio.play();
                playtime = setInterval(get_less_time,1000);
            }

            //暂停播放
            if(value ==4){
                voiceWrap.attr('data-value',3);
                voiceWrap.removeClass('voice-playing').addClass('voice-stop');
                myAudio.pause();
                clearInterval(playtime);
            }
        });
        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>