<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '通知记录';
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'fix-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        [
            'attribute'=>'nickname',
            'header'=>'接收者',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=> function($data)
            {
                if($data->userinfo->nickname){
                    return $data->userinfo->nickname .'&nbsp;&nbsp;'.Html::a(
                        Html::img( substr($data->userinfo->headimgurl,0,-1) .'46',['width'=>40]),
                        $data->userinfo->headimgurl.'?.jpg',
                        ['class'=>'fancybox','rel'=>'group1']
                    );
                }

            }
        ],
        [
            'attribute'=>'fromname',
            'header'=>'发布者',
            'value'=>'fromsend.name',
        ],
        'text',
        [
            'attribute'=>'is_read',
            'filter'=>['Y'=>'已读','N'=>'未读'],
            'value'=>function($data)
            {
                return $data->is_read == 'Y'? '已读':'未读';
            }
        ],
        [
            'attribute' => 'add_time',
            'format'=>['date','php:Ymd H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:80px'],
            'template' => '{delete}',
            'buttons' => [
                'delete' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',$url,['title'=>'删除']);

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
