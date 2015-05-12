<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '待维修列表';
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
            'attribute'=>'cover',
            'header'=>'故障图片',
            'format'=>'html',
            'value'=>function($data)
            {
                return Html::img($data->cover,['width'=>40]);
            }
        ],
        [
            'attribute'=>'type',
            'value'=>function($data)
            {
                return ConfigBase::getFaultStatus($data->type);
            }
        ],
        [
            'attribute'=>'machine.cover',
            'header'=>'机器',
            'format'=>'html',
            'value'=>function($data)
            {
                if( isset($data->machine->cover )  )
                    return Html::img($data->machine->cover,['width'=>40]);
            }
        ],

        'machine.brand',
        'machine.type',
        'machine.serial_id',
        'machine.maintain_time',
        [
            'attribute' => 'add_time',
            'header'=>'申请时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:120px'],
            'template' => '{allot} &nbsp; {delete}',
            'buttons' => [
                'allot'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-repeat"></i>',$url,['title'=>'分配维修']);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-remove"></i>',$url,[
                        'title'=>'删除',
                        'data-method'=>'post',
                        'data-confirm'=>'确定删除？',
                        'data-pjax'=>0
                    ]);
                },
            ]
        ]

    ],
]);

?>
