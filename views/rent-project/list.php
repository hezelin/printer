<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '租机方案列表';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div><a href="<?=Url::toRoute(['add'])?>" class="btn btn-info">添加租赁方案</a></div>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'brand_name',
        'model',
        'lowest_expense',
        'black_white',
        'colours',
        [
            'attribute'=>'cover',
            'format' => 'html',
            'content' => function($data){
                return \app\models\config\Tool::getImage($data->cover,100,true);
            }
        ],
        [
            'attribute'=>'is_show',
            'filter'=>[1=>'显示',0=>'不显示'],
            'value'=>function($data)
            {
                return $data->is_show==1? '显示':'不显示';
            }
        ],
        [
            'attribute'=>'add_time',
            'format'=>['date','php:Y-m-d H:i'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:120px'],
            'template' => '{view} &nbsp; {update} &nbsp; {delete}',
            'buttons' => [
                'update' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-edit"></span>',Url::toRoute(['update','id'=>$key,'url'=>Yii::$app->request->get('url')]),['title'=>'修改']);
                },
                'delete' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::toRoute(['delete','id'=>$key,'url'=>Yii::$app->request->get('url')]),[
                        'title'=>'删除',
                        'data-confirm'=>'确定删除？',
                        'data-pjax'=>0,
                        'data-method'=>'post'
                    ]);
                },
            ]
        ]
    ],
]);
?>

<?php
// fancybox 预览图片
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