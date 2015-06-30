<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\ConfigBase;
$this->title = '维修配件列表';
?>

<div >
    <ul class="nav nav-tabs" >
        <li class="active"><a href="javascript:void(0)" >维修配件列表</a></li>
        <li><a href="<?=Url::toRoute(['recycle'])?>" >已回收配件</a></li>
    </ul>
</div>
<p>&nbsp;</p>
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'id' => 'fix-list',
    'columns' => [
        [
            'attribute'=>'id',
            'label'=>'id',
        ],
        [
            'attribute'=>'cover_images',
            'headerOptions'=>['style'=>'width:160px'],
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'label'=>'配件图片',
            'value'=>function($data)
            {
                $covers = json_decode($data->product->cover_images,true);
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group1']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'name',
            'value'=>'product.name',
            'label'=>'名称',
        ],
        [
            'attribute'=>'market_price',
            'value'=>'product.market_price',
            'label'=>'成本价格',
        ],
        [
            'attribute'=>'price',
            'value'=>'product.price',
            'label'=>'销售价格',
        ],
        [
            'attribute'=>'cover',
            'headerOptions'=>['style'=>'width:200px'],
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'label'=>'故障资料',
            'value'=>function($model)
            {
                if( !isset($model->fault->cover)) return '无';
                $covers = json_decode($model->fault->cover,true);
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group1']);
                }
                return ConfigBase::getFaultStatus($model->fault->type).
                        join("\n",$html).
                        $model->fault->desc .
                        Html::a('详情', Url::toRoute(['/service/process','id'=>$model->fault_id]), ['class'=>'btn btn-sm btn-info']);
            }
        ],
        [
            'label'=>'维修员资料',
            'value'=>function($model)
            {
                if(isset($model->maintainer->name)){
                    return $model->maintainer->name.' , '.$model->maintainer->phone;
                }
            }
        ],
        [
            'attribute'=>'status',
            'headerOptions'=>['style'=>'width:120px'],
            'format'=>'html',
            'filter'=>\app\modules\shop\models\Shop::getParts(),
            'value'=>function($model){
                switch($model->status){
                    case 1: $btn = " &nbsp; ".Html::a('发货',
                            Url::toRoute(['status',
                                'id'=>$model->id,
                                'fault_id'=>Yii::$app->request->get('fault_id'),
                                'status'=>$model->fault_id>0? 3:2
                            ]),
                            [
                            'class'=>'btn btn-sm btn-info',
                        ]); break;
                    case 2:
                    case 3:
                    case 4:
                        $btn = " &nbsp; ".Html::a('绑定',
                            Url::toRoute(['status',
                                'id'=>$model->id,
                                'fault_id'=>Yii::$app->request->get('fault_id'),
                                'status'=>10
                            ]),
                            [
                                'class'=>'btn btn-sm btn-info',
                            ]); break;
                    case 11: $btn = " &nbsp; ".Html::a('回收',Url::toRoute(['status','id'=>$model->id,'type'=>'fault']),[
                            'class'=>'btn btn-sm btn-info',
                        ]);break;
                    default: $btn ='';
                }
                return \app\modules\shop\models\Shop::getParts($model->status).$btn;
            },
        ],
        [
            'attribute'=>'apply_time',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'attribute'=>'bing_time',
            'label'=>'绑定时间',
            'value'=>function($model){
                return $model->bing_time? date('Y-m-d H:i',$model->bing_time):'无';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:150px'],
            'template' => '{remark} &nbsp; {log} &nbsp; {qrcode} &nbsp; {delete}',
            'buttons' => [
                'remark' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-leaf"></span>','#',[
                        'title'=>'配件备注',
                        'key-id'=>$key,
                        'class'=>'close-model'
                    ]);
                },
                'qrcode' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['code/parts','id'=>$key,'item_id'=>$model->item_id,'wx_id'=>$model->wx_id]),['title'=>'配件二维码']);
                },
                'log' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-exclamation-sign"></span>',Url::toRoute(['log','id'=>$key]),['title'=>'查看记录']);
                },
                'delete' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::toRoute(['delete','id'=>$key]),[
                        'title'=>'删除',
                        'data-confirm'=>'确定删除？',
                        'data-pjax'=>0,
                        'data-method'=>'post'
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
    'header' => '配件备注',
    'id' => 'my-modal-cancel',
    'size' => 'modal-md',
    'toggleButton' => false,
    'footer' => '
        <button id="cancel-btn" type="button" class="btn btn-primary">备注</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    ',
]);
echo Html::tag('p','',['class'=>'text-danger','id'=>'cancel-status']);
echo Html::beginForm('','',['class'=>'form-horizontal']);
echo Html::input('text','service_cancel','',['placeholder'=>'不超过200字','class'=>'form-control','id'=>'cancel-text']);
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
        var keyId;
        $('#fix-list .close-model').click(function(){
            keyId = $(this).attr('key-id');
            $('#my-modal-cancel').modal('show');
            return false;
        });

        $('#cancel-btn').click(function(){
            var text = $.trim($('#cancel-text').val());
            if(!text){
                $('#cancel-status').text('请输入备注内容！');
                $('#cancel-text').focus();
                return false;
            }
            $.post(
                '<?=Url::toRoute(['remark'])?>?id='+keyId,
                {'text':text},
                function(res){
                    if(res.status == 1){
                        $('#my-modal-cancel').modal('hide');
                        alert('备注成功');
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