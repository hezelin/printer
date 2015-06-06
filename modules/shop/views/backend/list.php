<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
$this->title = '商品列表';

?>


<?= GridView::widget([
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
        'name',
        'market_price',
        'price',
        [
            'attribute'=>'add_attr',
            'headerOptions'=>['style'=>'width:160px'],
            'format' => 'html',
            'value'=>function($data)
            {
                $attr = json_decode($data->add_attr,true);
                $html = [];
                foreach($attr as $row){
                    $html[] = Html::tag('div',$row['name'].' : '.$row['value']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'category_id',
            'headerOptions'=>['style'=>'width:80px'],
            'filter' => $category,
        ],
        [
            'attribute'=>'status',
            'headerOptions'=>['style'=>'width:80px'],
            'filter' => [1=>'上架中',2=>'下架中'],
            'value' => function($data)
            {
                return $data->status==1? '上架中':'下架中';
            }
        ],
        [
            'attribute'=>'add_time',
            'format'=>['date','Y-H-d H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:120px'],
            'template' => '{view} &nbsp; {status} &nbsp; {update} &nbsp; {delete}',
            'buttons' => [
                'status' => function($url,$model,$key){
                    $icon = $model->status == 1? 'glyphicon glyphicon-arrow-down':'glyphicon glyphicon-arrow-up';
                    return Html::a('<i class="'.$icon.'"></i>',Url::toRoute(['status','id'=>$key,'status'=>$model->status]),[
                        'title' => $model->status == 1? '下架':'上架'
                    ]);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-trash"></i>',$url,[
                        'title'=>'删除商品',
                        'data-method'=>'post',
                        'data-pjax'=>0,
                        'data-confirm'=>'确定删除这个商品？'
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


