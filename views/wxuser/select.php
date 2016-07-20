<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '微信用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info">
    1、选择微信关注的用户租赁，不需要用户扫描绑定微信<br/>
    2、新建用户资料，需要用户扫描绑定微信 <a href="<?=$fromUrl?>" class="btn btn-sm btn-success">新建资料</a>
</div>
<?php

echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'fix-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'nickname',
        [
            'attribute'=>'headimgurl',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
                return Html::a( Html::img( substr($data->headimgurl,0,-1) .'46',['width'=>40]),
                    $data->headimgurl.'?.jpg',
                    ['class'=>'fancybox','rel'=>'group1']
                );
            }
        ],
        [
            'attribute'=>'sex',
            'filter'=>['未知','男','女'],
            'value'=>function($data){
                if( isset($data->sex ))
                    switch($data->sex)
                    {
                        case 1: return '男';
                        case 2: return '女';
                        default:return '未知';
                    }
            }
        ],
        'city',
        'province',
        'country',
        [
            'attribute' => 'subscribe_time',
            'format'=>['date','php:Ymd H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:80px'],
            'template' => '{select}',
            'buttons' => [
                'select'=>function($url,$model,$key){
                    return Html::a('选择','#',['class'=>'btn btn-success select-btn','openid'=>$key['openid']]);
                },
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

<script>
<?php $this->beginBlock('JS_END') ?>
    var fromUrl = '<?=$fromUrl?>';
    $('.select-btn').click(function(){
        document.location.href = fromUrl+'openid='+$(this).attr('openid');
    });
<?php $this->endBlock();?>
</script>
<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>