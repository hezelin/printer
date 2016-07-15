<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '维修员携带列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div >
    <ul class="nav nav-tabs" >
        <li><a href="<?=Url::toRoute(['list'])?>" >维修配件列表</a></li>
        <li class="active"><a href="javascript:void(0)" >维修员携带列表</a></li>
    </ul>
</div>
<p>&nbsp;</p>
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
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
                $covers = json_decode($data->parts->product->cover_images,true);
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group1']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'name',
            'value'=>'parts.product.name',
            'label'=>'名称',
        ],
        [
            'attribute'=>'market_price',
            'value'=>'parts.product.market_price',
            'label'=>'成本价格',
        ],
        [
            'attribute'=>'price',
            'value'=>'parts.product.price',
            'label'=>'销售价格',
        ],
        [
            'attribute'=>'status',
            'headerOptions'=>['style'=>'width:120px'],
            'format'=>'html',
            'filter'=>\app\modules\shop\models\Shop::getBringParts(),
            'value'=>function($data){
                $btn = $data->status == 1? "<br/>".Html::a('<i class="glyphicon glyphicon-ok"></i>',Url::toRoute(['pass','id'=>$data->parts_id,'type'=>'bring']),[
                        'class'=>'btn btn-sm btn-info',
                        'title'=>'通过'
                    ]).'&nbsp;'.Html::a('<i class="glyphicon glyphicon-remove"></i>',Url::toRoute(['nopass','id'=>$data->parts_id,'type'=>'bring']),[
                        'class'=>'btn btn-sm btn-warning',
                        'title'=>'不通过'
                    ]):'';
                return \app\modules\shop\models\Shop::getBringParts($data->status).$btn;
            },
        ],
        [
            'attribute'=>'update_time',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'attribute'=>'add_time',
            'label'=>'申请时间',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'label'=>'维修员资料',
            'value'=>function($data)
            {
                return $data->maintainer->name. ',' .$data->maintainer->phone;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:150px'],
            'template' => '{remark} &nbsp; {log} &nbsp; {qrcode} &nbsp; {go} &nbsp; {delete}',
            'buttons' => [
                'remark' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-leaf"></span>',Url::toRoute(['remark','id'=>$key]),['title'=>'配件备注']);
                },
                'qrcode' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['code/parts','id'=>$key,'item_id'=>$model->parts->item_id,'wx_id'=>$model->parts->wx_id]),['title'=>'配件二维码']);
                },
                'go' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-wrench"></span>',Url::toRoute(['go','id'=>$key]),['title'=>'绑定机器']);
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