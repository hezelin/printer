<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
use yii\bootstrap\Modal;


$this->title = '维修列表';
$this->params['breadcrumbs'][] = $this->title;
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

<?php if(Yii::$app->request->get('machine_id')):?>
    <div class="alert alert-info">正在筛选机器编号 <span class="badge"><?=Yii::$app->request->get('machine_id')?></span> 数据</div>
<?php elseif(Yii::$app->request->get('client_no')):?>
    <div class="alert alert-info">正在筛选客户编号 <span class="badge"><?=Yii::$app->request->get('client_no')?></span> 数据</div>
<?php else:?>
<div>
    <ul class="nav nav-tabs" >
        <li <?php if(!Yii::$app->request->get('process')) echo 'class="active"';?>><a href="<?=Url::toRoute(['list'])?>" >维修中</a></li>
        <li <?php if(Yii::$app->request->get('process')==2) echo 'class="active"';?>><a href="<?=Url::toRoute(['list','process'=>2])?>" >等待评价</a></li>
        <li <?php if(Yii::$app->request->get('process')==3) echo 'class="active"';?>><a href="<?=Url::toRoute(['list','process'=>3])?>" >已完成</a></li>
        <li><a href="<?=Url::toRoute(['cancel-list'])?>" >已取消</a></li>
    </ul>
    <p>&nbsp;</p>
</div>
<?php endif;?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'fix-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
//        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute'=>'machine_id',
            'format' => 'html',
            'content'=>function($model){
                return Html::a($model->machine_id,['machine/view','id'=>$model->machine_id],[
                    'title'=>'查看机器详情',
                    'target'=>'_blank',
                ]);
            }
        ],
        [
            'attribute'=>'series_id',
            'format' => 'html',
            'content'=>function($model){
                if($model->series_id)
                    return Html::a($model->series_id,['/service/list','client_no'=>$model->series_id],[
                        'title'=>'查看编号所有维修',
                        'target'=>'_blank',
                    ]);
                return '<span class="not-set">(未设置)</span>';
            }
        ],
        [
            'attribute'=>'user_name',
            'label'=>'用户姓名',
        ],
        [
            'attribute'=>'cover',
            'header'=>'故障图片',
            'headerOptions'=>['style'=>'width:160px'],
            'content'=>function($data)
            {
                if(!$data->content) return '没有图片';
                $contents = json_decode($data->content,true);
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
            'attribute'=>'type',
            'filter'=>ConfigBase::$faultStatus,
            'value'=>function($data)
            {
                return ConfigBase::getFaultStatus($data->type);
            }
        ],
        [
            'attribute'=>'desc',
            'content'=>function($data)
            {
                $li[] = '<li>'.$data->desc.'</li>';
                if($data->remark)
                    $li[] = '<li class="li-highlight">留言：'.$data->remark.'</li>';
                return '<ul class="list-text">'.join("\n",$li).'</ul>';
            }
        ],
        [
            'attribute'=>'cover',
            'header'=>'机器图片',
            'content'=>function($data)
            {
                if( isset($data->cover )  )
                    return Html::a(Html::img($data->cover,['width'=>40]),str_replace('/s/','/m/',$data->cover),['class'=>'fancybox','rel'=>'group1']);
            }
        ],
        [
            'attribute'=>'model_name',
            'label'=>'机型',
            'content'=>function($model) {
                return $model->brand_name . $model->model_name;
            }
        ],
        'maintain_count',
        [
            'attribute'=>'status',
            'header'=>'进度',
            'headerOptions'=>['style'=>'width:120px'],
            'filter'=>ConfigBase::$fixStatus,
            'format'=>'html',
            'value'=>function($data)
            {
                if( $data->status >= 3){
                    $maintainer = (new \yii\db\Query())
                        ->select('name')
                        ->from('tbl_user_maintain')
                        ->where('wx_id=:wid and openid=:openid',[':openid'=>$data->openid,':wid'=>$data->weixin_id])
                        ->scalar();
                    return '维修员：'.$maintainer.'<br/>'.ConfigBase::getFixStatus($data->status);
                }
                return ConfigBase::getFixStatus($data->status);
            }
        ],
        [
            'attribute' => 'add_time',
            'header'=>'维修时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:110px'],
            'template' => '{process} &nbsp; {switch} &nbsp; {delete} &nbsp; {qrcode} <br/>{fault}',
            'buttons' => [
                'process'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',$url,['title'=>'查看进度']);
                },
                'switch'=>function($url,$model,$key){
                    if($model->status <8 && $model->status > 1)
                        return Html::a('<i class="glyphicon glyphicon-random"></i>','#',[
                            'title'=>'更改维修员',
                            'key-id'=>$key,
                            'class'=>'allot-model'
                        ]);
                    else
                        return Html::tag('span','<i class="glyphicon glyphicon-random"></i>',[
                            'title'=>'更改维修员',
                            'class'=>'my-disabled'
                        ]);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',$url,[
                        'title'=>'关闭维修申请',
                        'class'=>'close-model',
                        'key-id'=>$key
                    ]);
                },
                'qrcode' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['/code/machine','id'=>$model->machine_id]) ,['title'=>'机器二维码']);
                },
                'fault' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-screenshot"></span>',Url::toRoute(['/service/list','machine_id'=>$model->machine_id]) ,['title'=>'机器维修记录']);
                },
            ]
        ]

    ],
]);

/*
 * 取消任务 模态框
 */
Modal::begin([
    'header' => '关闭维修申请',
    'id' => 'my-modal-cancel',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="cancel-btn" type="button" class="btn btn-primary">取消维修</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','取消维修并且给用户和管理员发送通知',['class'=>'text-primary']);
echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'取消原因','class'=>'form-control','id'=>'cancel-text']);
echo Html::endForm();

Modal::end();


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


// 分配维修
Modal::begin([
    'header' => '分配任务',
    'id' => 'my-modal',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo GridView::widget([
    'dataProvider'=> $fixProvider,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'name',
        'phone',
        [
            'attribute'=>'wait_repair_count',
            'contentOptions'=>['class'=>'repair-count'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '分配',
            'headerOptions'=>['style'=>'width:120px'],
            'template' => '{select}',
            'buttons' => [
                'select'=>function($url,$model,$key){

                    return Html::a('<i class="glyphicon glyphicon-ok"></i>','javascript:void(0);',[
                        'title'=>'分配维修',
                        'class'=>'select-maintain',
                        'key-wid'=>$key['wx_id'],
                        'key-openid'=>$key['openid'],
                        'data-method'=>'post',
                    ]);
                },
            ]
        ]
    ],
]);

Modal::end();
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
        var allotTr;
        var keyId;

        $('#fix-list .close-model').click(function(){
            keyId = $(this).attr('key-id');
            allotTr = $(this).closest('tr');
            $('#my-modal-cancel').modal('show');
            return false;
        });

        $('#fix-list .allot-model').click(function(){
            allotTr = $(this).closest('tr');
            keyId = $(this).attr('key-id');
            $('#my-modal').modal('show');
            return false;
        });
        $('#my-modal .select-maintain').click(function(){
            $(this).html('<img src="/images/loading.gif">');
            var $this = $(this);
            var wid = $(this).attr('key-wid');
            var openid = $(this).attr('key-openid');
            var re_count = $(this).closest('tr').children('.repair-count');
            $.post(
                '<?=Url::toRoute(['switch'])?>',
                {'id':keyId,'wid':wid,'openid':openid},
                function(res){
                    if(res.status == 1){
                        re_count.text( parseInt(re_count.text()) + 1 );
                        setTimeout(function(){
                            $this.html('<i class="glyphicon glyphicon-ok"></i>');
                            $('#my-modal').modal('hide');
                            allotTr.remove();
                        },1000);
                    }
                    else
                        alert(res.msg);
                },'json'
            );
            return false;
        });

        $('#cancel-btn').click(function(){
            var text = $.trim($('#cancel-text').val());
            if(!text){
                $('#cancel-status').text('请输入取消原因！');
                $('#cancel-text').focus();
                return false;
            }
            $.post(
                '<?=Url::toRoute(['delete','id'=>$wid])?>?fid='+keyId,
                {'type':1,'text':text},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        allotTr.remove();
                    }
                    else
                        alert(res.msg);
                },'json'
            );
        })


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