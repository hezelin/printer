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
                return Html::a(Html::img($data->cover,['width'=>40]),$data->cover,[
                    'target'=>'_blank'
                ]);
            }
        ],
        [
            'attribute'=>'type',
            'value'=>function($data)
            {
                return ConfigBase::getFaultStatus($data->type);
            }
        ],
        'desc',
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
            'attribute'=>'status',
            'header'=>'进度',
            'headerOptions'=>['style'=>'width:100px'],
            'filter'=>ConfigBase::$fixStatus,
            'value'=>function($data)
            {
                return ConfigBase::getFixStatus($data->status);
            }
        ],
        [
            'attribute' => 'add_time',
            'header'=>'申请时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:80px'],
            'template' => '{process} &nbsp; {delete}',
            'buttons' => [
                'process'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',$url,['title'=>'查看进度']);
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
