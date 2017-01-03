<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '收租记录';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
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
                    return Html::a($model->series_id,['/charge/list','client_no'=>$model->series_id],[
                        'title'=>'查看编号所有收租',
                        'target'=>'_blank',
                    ]);
                return '<span class="not-set">(未设置)</span>';
            }
        ],
        'black_white',
        'colour',
        'total_money',
        'exceed_money',
        'brand_name',
        'model_name',
        'user_name',
        'address',
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
            'format'=>['date','Y-MM-dd H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:140px'],
            'template' => '{log} &nbsp;{update}',
            'buttons' => [
                'log' => function($url,$model,$key){
                    return Html::a('收租记录',Url::toRoute(['log','machine_id'=>$model->machine_id]),['class'=>'btn btn-default btn-sm'] );
                },
                'update' => function($url,$model,$key){
                    return Html::a('修改',$url,['class'=>'btn btn-primary btn-sm'] );
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