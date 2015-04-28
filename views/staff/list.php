<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
use app\components\LoadingWidget;

$this->title = '我的公众号';
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
            'attribute' => 'userinfo.subscribe_time',
            'format'=>['date','php:Y-md H:i']
        ],
        [
            'attribute' => 'add_time',
            'header'=>'绑定时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'template' => '{unbind}',
            'buttons' => [
                'unbind' => function($url,$model,$key){
                    return Html::a('解除绑定',$url,[
                        'data-method'=>'post',
                        'data-confirm'=>'确定解除绑定？',
                        'data-pjax'=>0
                    ]);
                }
            ]
        ]

    ],
]);

?>
