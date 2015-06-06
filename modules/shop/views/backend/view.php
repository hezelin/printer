<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '商品详情';
?>
<?php

function getMachine($model){
    return Html::a(Html::img($model->machine->cover,['width'=>40]),$model->machine->cover,['class'=>'fancybox','rel'=>'group']) .
    ' , ' . $model->machine->brand .
    ' , ' . $model->machine->type .
    ' , ' . $model->machine->serial_id
        ;
}

function getAttr($str)
{
    $attr = json_decode($str,true);
    $html = [];
    foreach($attr as $row){
        $html[] = Html::tag('div',$row['name'].' : '.$row['value']);
    }
    return join("\n",$html);
}

function getCover($str)
{
    $covers = json_decode($str,true);

    $html = [];
    foreach($covers as $cover){
        $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group']);
    }
    return join("\n",$html);
}

?>

<?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'table table-striped detail-view'],
    'attributes' => [
        'name',
        'market_price',
        'price',
        [
            'attribute'=>'add_attr',
            'headerOptions'=>['style'=>'width:160px'],
            'format' => 'html',
            'value'=>getAttr($model->add_attr)
        ],
        [
            'attribute'=>'cover_images',
            'format'=>['html', ['Attr.AllowedRel' => 'group']],
            'value'=> getCover($model->cover_images)
        ],
        [
            'attribute'=>'describe',
            'format'=>'html',
        ],
    ],
    'template' => '<tr><th style="width:120px;">{label}</th><td>{value}</td></tr>',
]);

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


