<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
use app\components\LoadingWidget;

$this->title = '我的公众号';
?>

<div class="alert alert-danger" role="alert">
    数据已过期，请重新选择公众号操作！
</div>

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
            'contentOptions' => ['class'=>'ct-status'],
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
            'class' => 'yii\grid\DataColumn',
            'header' => '操作',
            'format' => 'html',
            'value'=> function($data) {
                return Html::a('选择', Url::to(['console/view','wx_id'=>$data->id,'url'=>Yii::$app->request->get('url')]));
            }
        ]
    ],
]);

?>
