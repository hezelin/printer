<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = '积分列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'fix-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        [
            'attribute'=>'nickname',
            'value'=>'userinfo.nickname',
            'header'=>'昵称',
        ],
        [
            'attribute'=>'headimgurl',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'header'=>'头像',
            'value'=>function($data)
            {
                if($data->userinfo->headimgurl)
                return Html::a( Html::img( substr($data->userinfo->headimgurl,0,-1) .'46',['width'=>40]),
                    $data->userinfo->headimgurl.'?.jpg',
                    ['class'=>'fancybox','rel'=>'group1']
                );
            }
        ],
        'score',
        [
            'attribute'=>'type',
            'filter'=>\app\models\ConfigBase::$scoreFromStatus,
            'value'=>function($data)
            {
                return \app\models\ConfigBase::getScoreFromStatus($data->type);
            }
        ],
        [
            'attribute' => 'add_time',
            'format'=>['date','php:Ymd H:i']
        ],
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
