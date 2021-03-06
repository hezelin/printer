<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = '待收租租借';
?>
<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'id'=>'apply-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        [
            'attribute'=>'name',
            'header'=>'租借者',
        ],
        [
            'attribute'=>'phone',
            'header'=>'手机',
        ],
        [
            'label'=>'微信资料',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value' => function($model){
                $sex=[1=>'男',2=>'女','0'=>'未知'];
                return Html::tag('span',$model['nickname'].'&nbsp;,&nbsp;').
//                       Html::tag('span',$sex[ $model['sex']].'&nbsp;,&nbsp;').
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
                if(!$covers) return '无机器';
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group1']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'model_name',
            'label'=>'机器型号',
            'value' => function($model)
            {
                return $model['brand_name']? $model['brand_name'] . '/' . $model['model_name']:'';
            }
        ],
        [
            'attribute'=>'project_id',
            'label'=>'租赁方案',
            'format'=>'html',
            'value'=>function($model)
            {
                $row[] = Html::tag('div','最低消费：'.$model['monthly_rent'].'元');
                $row[] = Html::tag('div','黑白：'.$model['black_white'].'元/张');
                if($model['colours'])
                    $row[] = Html::tag('div','彩色：'.$model['colours'].'元/张');
                return join("\n",$row);
            }
        ],
        [
            'attribute' => 'first_rent_time',
            'header'=>'收租时间',
            'format' => 'html',
            'value'=>function($model) {
                return date('Y-m-d H:i', $model['first_rent_time']);
            }
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