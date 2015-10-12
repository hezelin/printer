<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '机器模型管理';
?>

<div >
    <ul class="nav nav-tabs" >
        <li><a href="<?=Url::toRoute(['add','url'=>Yii::$app->request->get('url')])?>" >添加</a></li>
        <li class="active"><a href="javascript:void(0)" >列表</a></li>
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
        'id',
        [
            'attribute'=>'cover_images',
            'headerOptions'=>['style'=>'width:160px'],
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
                $covers = json_decode($data->cover_images,true);
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group1']);
                }
                return join("\n",$html);
            }
        ],
        'type',
        [
            'attribute'=>'brand_id',
            'filter'=>ConfigBase::getMachineBrand(),
            'value'=>function($data){
                return ConfigBase::getMachineBrand($data->brand_id);
            }
        ],
        'machine_count',
        'function',
        [
            'attribute'=>'else_attr',
            'headerOptions'=>['style'=>'width:160px'],
            'format' => 'html',
            'value'=>function($data)
            {
                $attr = json_decode($data->else_attr,true);
                $html = [];
                foreach($attr as $row){
                    $html[] = Html::tag('div',$row['name'].' : '.$row['value']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'buy_date',
            'format'=>['date','php:Y-m-d'],
        ],
        [
            'attribute'=>'add_time',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:120px'],
            'template' => '{view} &nbsp; {add} &nbsp; {update} &nbsp; {delete}',
            'buttons' => [
                'add' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-plus"></span>',Url::toRoute(['machine/add','model_id'=>$key]),['title'=>'添加机器']);
                },
                'update' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-edit"></span>',Url::toRoute(['update','id'=>$key,'url'=>Yii::$app->request->get('url')]),['title'=>'修改']);
                },
                'delete' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::toRoute(['delete','id'=>$key,'url'=>Yii::$app->request->get('url')]),[
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