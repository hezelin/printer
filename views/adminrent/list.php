<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = '租借列表';
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        [
            'attribute'=>'type',
            'header'=>'机型',
            'value'=>'machine.machineModel.type',
        ],
        [
            'attribute'=>'series_id',
            'header'=>'编号',
            'value'=>'machine.series_id',
        ],
        'monthly_rent',
        'black_white',
        'colours',
        'name',
        'phone',
        [
            'attribute' => 'due_time',
            'format' => ['date', 'php:Y-m-d'],
        ],
        'address',
        [
            'attribute' => 'add_time',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions' => ['style'=>'width:100px'],
            'template' => '{update} &nbsp; {delete}',
            'buttons' => [
                'update' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-edit"></span>',$url,['title'=>'修改']);
                },
                /*'change' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-sort"></span>',$url,['title'=>'更换机器']);
                },*/
                'delete' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>',$url,[
                        'title'=>'删除关系',
                        'data-method'=>'post',
                        'data-confirm'=>'确定删除吗？',
                        'data-pjax'=>0
                    ]);
                },
            ]
        ]

    ],
]);

?>