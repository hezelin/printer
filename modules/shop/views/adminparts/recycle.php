<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '回收配件列表';
?>

<div >
    <ul class="nav nav-tabs" >
        <li><a href="<?=Url::toRoute(['list'])?>" >维修配件列表</a></li>
        <li class="active"><a href="javascript:void(0)" >已回收配件</a></li>
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
            'headerOptions'=>['style'=>'width:160px'],
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
                        $model->fault->desc;
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
                return \app\modules\shop\models\Shop::getParts($model->status);
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
                    return Html::a('<span class="glyphicon glyphicon-leaf"></span>',Url::toRoute(['remark','id'=>$key]),['title'=>'配件备注']);
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