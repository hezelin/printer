<?php
use yii\grid\GridView;
use yii\helpers\Html;

    $this->title = '租借申请';
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'name',
        'phone',
        [
            'label'=>'昵称',
            'attribute' => 'nickname',
            'value' => 'userInfo.nickname'
        ],
        [
            'attribute'=>'userInfo.headimgurl',
            'format'=>'html',
            'value'=>function($data)
            {
                return Html::a( Html::img( substr($data->userInfo->headimgurl,0,-1) .'46'),
                    $data->userInfo->headimgurl,
                    ['title'=>'查看大图','target'=>'_blank']
                );
            }
        ],
        [
            'attribute'=>'userInfo.sex',
            'value'=>function($data){
                switch($data->userInfo->sex)
                {
                    case 1: return '男';
                    case 2: return '女';
                    default:return '未知';
                }
            }
        ],
        [
            'attribute' => 'add_time',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:100px'],
            'template' => '{pass} &nbsp; {unpass}',
            'buttons' => [
                'pass' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-ok"></i>',$url,['title'=>'租借通过']);
                },
                'unpass' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',$url,['title'=>'不通过']);
                }
            ]
        ]

    ],
]);

?>