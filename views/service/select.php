<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
use yii\bootstrap\Modal;

$this->title = '选择维修';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'fix-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
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
        'desc',
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
            'headerOptions'=>['style'=>'width:100px'],
            'filter'=>ConfigBase::$fixStatus,
            'value'=>function($data)
            {
                return ConfigBase::getFixStatus($data->status);
            }
        ],
        [
            'attribute' => 'add_time',
            'header'=>'申请时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:80px'],
            'template' => '{select}',
            'buttons' => [
                'select'=>function($url,$model,$key){
                    return Html::a('选择','#',['class'=>'btn btn-success select-btn','fault_id'=>$model->id]);
                },
            ]
        ],
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

?>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var fromUrl = '<?=$fromUrl?>';
        $('.select-btn').click(function(){
            document.location.href = fromUrl+'fault_id='+$(this).attr('fault_id');
        });
        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>