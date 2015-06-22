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
        'name',
        'phone',
        [
            'label'=>'昵称',
            'attribute' => 'nickname',
            'value' => 'userinfo.nickname'
        ],
        [
            'attribute'=>'userinfo.headimgurl',
            'format'=>['html', ['Attr.AllowedRel' => 'group']],
            'value'=>function($data)
            {
                if( isset($data->userinfo->headimgurl ))
                return Html::a( Html::img( substr($data->userinfo->headimgurl,0,-1) .'46'),
                    $data->userinfo->headimgurl.'?.jpg',
                    ['rel'=>'group','class'=>'fancybox']
                );
            }
        ],
        [
            'attribute'=>'userinfo.sex',
            'value'=>function($data){
                if( isset($data->userinfo->sex ))
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
            'headerOptions'=>['style'=>'width:150px'],
            'template' => '{notify} &nbsp; {update} &nbsp; {unbind}',
            'buttons' => [
                'update'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-edit"></i>',$url,['title'=>'修改资料']);
                },
                'notify' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-envelope"></i>',Url::toRoute(['notify/send','openid'=>$key['openid'],'id'=>$key['wx_id']]),['title'=>'发送通知']);

                },
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
