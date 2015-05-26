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
    'id'=>'fix-list',
    'dataProvider'=> $dataProvider,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        [
            'attribute'=>'cover',
            'header'=>'故障图片',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
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
            'attribute'=>'machine.cover',
            'header'=>'机器',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
                if( isset($data->machine->cover )  )
                    return Html::a(Html::img($data->machine->cover,['width'=>40]),$data->machine->cover,['class'=>'fancybox','rel'=>'group1']);
            }
        ],

        'machine.brand',
        'machine.type',
        'machine.serial_id',
        'machine.maintain_time',
        [
            'attribute' => 'add_time',
            'header'=>'申请时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:120px'],
            'template' => '{allot} &nbsp; {delete}',
            'buttons' => [
                'allot'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-repeat"></i>','javascript:void(0);',[
                        'title'=>'分配维修',
                        'class'=>'allot-model',
                        'key-id'=>$key
                    ]);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',$url,[
                        'title'=>'删除',
                        'data-method'=>'post',
                        'data-confirm'=>'确定删除？',
                        'data-pjax'=>0
                    ]);
                },
            ]
        ]

    ],
]);

?>

<script>
    <?php $this->beginBlock('JS_END') ?>
        var allotTr;
        var keyId;
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
                '<?=Url::toRoute(['allot'])?>',
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
    <?php $this->endBlock();?>
</script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>

<?php
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