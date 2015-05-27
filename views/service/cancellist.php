<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '取消操作日志';
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
            'attribute'=>'fault.cover',
            'header'=>'故障图片',
            'headerOptions'=>['style'=>'width:180px'],
            'format'=>['html', ['Attr.AllowedRel' => 'group']],
            'value'=>function($data)
            {
                $covers = json_decode($data->fault->cover,true);
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),$cover,['class' => 'fancybox','rel'=>'group']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'faulttype',
            'header'=>'故障类型',
            'headerOptions'=>['style'=>'width:100px'],
            'filter'=>ConfigBase::$faultStatus,
            'value'=>function($data)
            {
                return ConfigBase::getFaultStatus($data->fault->type);
            }
        ],
        [
            'label'=>'故障描述',
            'attribute' => 'desc',
            'value' => 'fault.desc'       //加上这段代码
        ],
        [
            'attribute'=>'status',
            'header'=>'进度',
            'headerOptions'=>['style'=>'width:100px'],
            'filter'=>ConfigBase::$fixStatus,
            'value'=>function($data)
            {
                return ConfigBase::getFixStatus($data->fault->status);
            }
        ],
        [
            'attribute' => 'fault.add_time',
            'header'=>'申请时间',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        [
            'attribute' =>'add_time',
            'format' => ['date', 'php:Y-m-d H:i'],
        ],
        'reason',
        'opera'
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
