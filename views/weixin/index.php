<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '我的公众号';
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'name',
        'wx_num',
        [
            'class' => 'yii\grid\DataColumn',
            'attribute' =>'vip_level',
            'value' => function($data){
                return ConfigBase::getVip($data->vip_level);
            }
        ],
        [
            'class' => 'yii\grid\DataColumn',
            'attribute'=>'status',
            'format' => 'html',
            'value' => function($data){

                $a = Html::tag('i','&nbsp;',['class'=>'circle']);
                if($data->due_time < time())
                    return Html::tag('span',$a . '已到期',['class'=>'due-status']);

                switch($data->status){
                    case 1: return Html::tag('span',$a . '未开通',['class'=>'init-status']);
                    case 2: return Html::tag('span',$a . '运行中',['class'=>'run-status']);
                    case 3: return Html::tag('span',$a . '已停止',['class'=>'stop-status']);
                    case 4: return Html::tag('span',$a . '已到期',['class'=>'duo-status']);
                }
            }
        ],
        [
            'attribute'=>'create_time',
            'format' => ['date', 'php:Y-m-d'],
        ],
        [
            'class' => 'yii\grid\DataColumn',
            'attribute'=>'due_time',
            'format' => 'html',
            'value' => function($data){
                $time = time();
                $dueTime = $data->due_time;
                $icon = '<i class="glyphicon glyphicon-time"></i> ';
                if($dueTime <= $time) return Html::tag('span',$icon.date('Y-m-d H:i',$dueTime),['class'=>'red']);
                else if( ($dueTime - 259200) < $time  )
                    return Html::tag('span',$icon.date('Y-m-d H:i',$dueTime),['class'=>'orange']);
                return Html::tag('span',$icon.date('Y-m-d H:i',$dueTime));
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{renew} | {upgrade}  &nbsp; &nbsp; {console} &nbsp; {view} &nbsp; {update} &nbsp; {delete} &nbsp; {start} &nbsp; {stop}',
            'buttons' => [
                'renew' => function($url,$model,$key){
                    if($model->status == 1)
                        return Html::a('开通',$url);
                    return Html::a('续费', $url);
                },
                'upgrade' => function($url,$model,$key){
                    return Html::a('升级', $url);
                },
                'console' => function($url,$model, $key){
                    return Html::a('<span class="glyphicon glyphicon-home"></span>',Url::to(['console/view','id'=>$model->id]) ,['title'=>'控制台']);
                },
                'update' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-edit"></span>',$url,['title'=>'修改']);
                },
                'start' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-play"></span>',$url,[
                        'title'=>'启动',
                        'data-method'=>'post',
                        'data-pjax'=>'0',
                    ]);
                },
                'stop' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-stop"></span>',$url,['title'=>'停止']);
                }
            ]
        ]
    ],
]);
?>