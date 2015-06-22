<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
use yii\bootstrap\Modal;


$this->title = '待维修列表';
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
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
                if(!$data->cover) return '没有图片';
                $covers = json_decode($data->cover,true);
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),$cover,['class' => 'fancybox','rel'=>'group1']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'type',
            'value'=>function($data)
            {
                return ConfigBase::getFaultStatus($data->type);
            }
        ],
        'desc',
        [
            'attribute'=>'machine.machineModel.cover',
            'header'=>'机器',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
                if( isset($data->machine->machineModel->cover )  )
                    return Html::a(Html::img($data->machine->machineModel->cover,['width'=>40]),str_replace('/s/','/m/',$data->machine->machineModel->cover),['class'=>'fancybox','rel'=>'group1']);
            }
        ],

        'machine.machineModel.brand.name',
        'machine.machineModel.type',
        [
            'attribute'=>'series_id',
            'format'=>'html',
            'header'=>'系列号',
            'headerOptions'=>['style'=>'width:100px'],
            'value'=>function($model){
                return Html::a($model->machine->series_id,\yii\helpers\Url::toRoute(['machine/view','id'=>$model->machine_id]),['title'=>'查看机器详情']).
                Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-qrcode"></i>',\yii\helpers\Url::toRoute(['code/machine','id'=>$model->machine->id]),['title'=>'查看机器二维码']);
            }
        ],
        'machine.maintain_count',
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
            'template' => '{process} &nbsp; {delete}',
            'buttons' => [
                'process'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',$url,['title'=>'查看进度']);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',$url,[
                        'title'=>'关闭维修申请',
                        'class'=>'close-model',
                        'key-id'=>$key
                    ]);
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

?>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var allotTr;
        var keyId;
        $('#fix-list .close-model').click(function(){
            keyId = $(this).attr('key-id');
            allotTr = $(this).closest('tr');
            $('#my-modal-cancel').modal('show');
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

        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>