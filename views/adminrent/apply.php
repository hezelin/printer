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
            'value' => 'userinfo.nickname'
        ],
        [
            'attribute'=>'userinfo.headimgurl',
            'format'=>'html',
            'value'=>function($data)
            {
                return Html::a( Html::img( substr($data->userinfo->headimgurl,0,-1) .'46'),
                    $data->userinfo->headimgurl,
                    ['title'=>'查看大图','target'=>'_blank']
                );
            }
        ],
        [
            'attribute'=>'userinfo.sex',
            'value'=>function($data){
                switch($data->userinfo->sex)
                {
                    case 1: return '男';
                    case 2: return '女';
                    default:return '未知';
                }
            }
        ],
        'userinfo.city',
        'userinfo.province',
        'userinfo.country',
        [
            'attribute' => 'add_time',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:100px'],
            'template' => '{check}',
            'buttons' => [
                'check' => function($url,$model,$key){
                    return Html::a('处理申请',$url);
                }
            ]
        ]

    ],
]);

?>