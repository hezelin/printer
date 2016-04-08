<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '收租记录';

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'black_white',
        'colour',
        'total_money',
        'exceed_money',
        [
            'label'=>'机型',
            'format'=>'html',
            'value'=>function($model){
                if( isset( $model->rentApply->machine->machineModel ) )
                return $model->rentApply->machine->machineModel->brand->name . $model->rentApply->machine->machineModel->type;
            }
        ],
        'rentApply.name',
        'rentApply.address',
        [
            'attribute'=>'sign_img',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($model){
                return Html::a(Html::img($model->sign_img,['style'=>'width:40px;']),$model->sign_img,['class' => 'fancybox','rel'=>'group1']);
            }
        ],
        'name',
        [
            'attribute'=>'status',
            'filter'=>[1=>'待确认',2=>'已确认'],
            'options'=>['style'=>'width:100px'],
            'format'=>'html',
            'value'=>function($model){
                if( $model->status==1 )
                {
                    return Html::tag('p','待确认').Html::a('通过','javascript:void(0);',['class'=>'btn btn-info btn-sm btn-pass']);
                }
                return '已确认';
            }
        ],
        [
            'attribute'=>'add_time',
            'label'=>'记录时间',
            'value'=>function($model){
                return date('Y-m-d H:i',$model->add_time);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:100px'],
            'template' => '{log} &nbsp;{update}',
            'buttons' => [
                'log' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-info-sign"></i>',Url::toRoute(['log','machine_id'=>$model->machine_id]),['title'=>'收租记录'] );
                },
            ]
        ]
    ],
]); ?>

<?php
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
        $('.btn-pass').click(function(){
            var key =$(this).closest('tr').attr('data-key');
            var td = $(this).closest('td');
            $.post(
                '<?=Url::toRoute('pass')?>',
                {logId:key},
                function(resp){
                    if(resp.status == 1)
                        td.text('已通过')
                    else
                        alert(resp.msg);
                },'json'
            );
        })
        <?php $this->endBlock();?>
    </script>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>