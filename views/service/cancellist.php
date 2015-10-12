<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '取消操作日志';
?>
    <style>
        .list-text,.list-text li{  list-style: none;  padding: 0; margin: 0;  font-size: 14px;  }
        .list-text li{  height: 24px;  line-height: 24px;  width: 100%;  display: inline-block;  }
        .li-highlight{  color: #ff0000;  }
        .h-box-text{ width: 100%; margin-top: 8px;}
        .voice-wrap{width: 100%; float: left; position: relative; border-radius: 32px; background-color: #CCFFFF;
            height: 32px; cursor: pointer;}
        .voice-time{text-align:left; padding-left:10px; font-size:20px;height:32px;line-height:32px;color:#505050;}
        .voice-image{ margin:0 10px 0 5px;height:32px;width:32px;float:left; background: url(/images/voice.png) -120px 0 no-repeat;}
        .voice-start .voice-image{background-position: 0 0;}
        .voice-stop .voice-image{background-position: -40px 0;}
        .voice-playing .voice-image{background-position: -120px 0;}
        .voice-play .voice-image{background-position: -80px 0;}
    </style>

<div >
    <ul class="nav nav-tabs" >
        <li><a href="<?=Url::toRoute(['list'])?>" >维修中</a></li>
        <li><a href="<?=Url::toRoute(['list','process'=>2])?>" >等待评价</a></li>
        <li><a href="<?=Url::toRoute(['list','process'=>3])?>" >已完成</a></li>
        <li class="active"><a href="<?=Url::toRoute(['cancellist'])?>" >已取消</a></li>
    </ul>
</div>
<p>&nbsp;</p>

<?php
echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        [
            'attribute'=>'fault.cover',
            'header'=>'故障图片',
            'headerOptions'=>['style'=>'width:180px'],
            'content'=>function($data)
            {
                if( !isset($data->fault->content) ) return '<span class="not-set">（无设置）</span>';
                $contents = json_decode($data->fault->content,true);
                $html = [];
                if(isset($contents['cover']) && is_array($contents['cover']) )
                    foreach($contents['cover'] as $cover){
                        $html[] = Html::a(Html::img($cover,['width'=>40]),$cover,['class' => 'fancybox','rel'=>'group1']);
                    }

                if(isset($contents['voice'],$contents['voiceLen'])){
                    $html[] = '
                    <div class="h-box-text">
                        <div class="voice-wrap" data-value="3" data-time="'.$contents['voiceLen'].'" data-id="'.$data->id.'">
                            <div class="voice-image voice-playing"></div>
                            <p class="voice-time"><span class="voice-second">'.$contents['voiceLen'].'</span>＂</p>
                        </div>
                        <audio hidden="true" preload="auto" onended="play_ended()" id="myaudio'.$data->id.'">
                            <source src="'.$contents['voice'].'" type="audio/mpeg">
                            "不支持播放录音"
                        </audio>
                    </div>';
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'faulttype',
            'header'=>'故障类型',
            'headerOptions'=>['style'=>'width:100px'],
            'filter'=>ConfigBase::$faultStatus,
            'value'=>function($data)
            {
                if( !isset($data->fault->type) ) return;
                return ConfigBase::getFaultStatus($data->fault->type);
            }
        ],
        [
            'label'=>'故障描述',
            'attribute' => 'desc',
            'content'=>function($data){
                $li = [];
                if( isset($data->fault->desc) )
                    $li[] = '<li>'.$data->fault->desc.'</li>';
                if( isset($data->fault->remark))
                    $li[] = '<li class="li-highlight">留言：'.$data->fault->remark.'</li>';
                return '<ul class="list-text">'.join("\n",$li).'</ul>';
            }
        ],
        [
            'attribute'=>'status',
            'header'=>'进度',
            'headerOptions'=>['style'=>'width:100px'],
            'filter'=>ConfigBase::$fixStatus,
            'value'=>function($data)
            {
                if( !isset($data->fault->status) ) return;
                return ConfigBase::getFixStatus($data->fault->status);
            }
        ],
        [
            'attribute' => 'fault.add_time',
            'header'=>'申请时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'attribute' =>'add_time',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        'reason',
        'opera'
    ],
]);



// fancybox 图片预览插件

echo newerton\fancybox\FancyBox::widget([
    'target' => '.fancybox',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '100%',
        'maxHeight' => '100%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '100%',
        'height' => '100%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
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

                if( myAudio && oldVoice.attr('data-id') != voiceWrap.attr('data-id')){
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