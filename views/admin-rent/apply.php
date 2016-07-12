<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = '租借申请';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
//    'filterModel' => $searchModel,
    'id'=>'apply-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        [
            'attribute'=>'name',
            'header'=>'姓名',
        ],
        [
            'attribute'=>'phone',
            'header'=>'手机',
        ],
        [
            'label'=>'微信资料',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value' => function($model){
                $sex=[1=>'男',2=>'女','0'=>'未知',''=>'未知'];
                return Html::tag('span',$model['nickname'].'&nbsp;,&nbsp;').
                       Html::tag('span',$sex[ $model['sex']].'&nbsp;,&nbsp;').
                       Html::a(Html::img( substr($model['headimgurl'],0,-1).'46',['style'=>'width:40px']),$model['headimgurl'].'?.jpg',
                           ['rel'=>'group1','class' => 'fancybox']);
            }
        ],
        [
            'attribute'=>'images',
            'label'=>'机型图片',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($model){
                $covers = json_decode($model['images'],true);
                if( !is_array($covers) )
                    return '';
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group1']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'brand_name',
            'label'=>'品牌',
        ],
        [
            'attribute'=>'model',
            'label'=>'机器型号',
        ],
        [
            'attribute'=>'project_id',
            'label'=>'租赁方案',
            'format'=>'html',
            'value'=>function($model)
            {
                $row[] = Html::tag('div','最低消费：'.$model['lowest_expense'].'元');
                $row[] = Html::tag('div','黑白：'.\app\models\config\Tool::schemePrice($model['black_white']));
                if($model['colours'])
                    $row[] = Html::tag('div','彩色：'.\app\models\config\Tool::schemePrice($model['colours']));
                return join("\n",$row);
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
            'headerOptions' => ['style'=>'width:100px'],
            'template' => '{pass} &nbsp; {unpass}',
            'buttons' => [
                'pass' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-ok"></i>',Url::toRoute(['pass','id'=>$model['id']]),['title'=>'租借通过']);
                },
                'unpass' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',Url::toRoute(['unpass','id'=>$model['id']]),[
                        'title'=>'不通过',
                        'class'=>'close-model',
                        'key-id'=>$model['id'],
                    ]);
                }
            ]
        ]

    ],
]);



/*
 * 取消租借 模态框
 */
Modal::begin([
    'header' => '租借申请不通过',
    'id' => 'my-modal-cancel',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="cancel-btn" type="button" class="btn btn-primary">不通过</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','不通过并且给用户发送通知',['class'=>'text-primary']);
echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'资料不齐全无法联系','class'=>'form-control','id'=>'cancel-text']);
echo Html::endForm();

Modal::end();
?>

    <script>
        <?php $this->beginBlock('JS_END') ?>
        var allotTr;
        var keyId;
        $('#apply-list .close-model').click(function(){
            keyId = $(this).attr('key-id');
            allotTr = $(this).closest('tr');
            $('#my-modal-cancel').modal('show');
            return false;
        });

        $('#cancel-btn').click(function(){
            var text = $.trim($('#cancel-text').val());
            if(!text){
                $('#cancel-status').text('请输入不通过原因！');
                $('#cancel-text').focus();
                return false;
            }
            $.post(
                '<?=Url::toRoute(['nopass'])?>?rent_id='+keyId,
                {'text':text},
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